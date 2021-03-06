<?php

namespace StripeIntegration\Payments\Test\Integration\Frontend\CheckoutPage\CardsEmbedded\AuthorizeCapture\Normal;

use PHPUnit\Framework\Constraint\StringContains;

class UpdateCartTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->helper = $this->objectManager->get(\StripeIntegration\Payments\Helper\Generic::class);
        $this->stripeConfig = $this->objectManager->get(\StripeIntegration\Payments\Model\Config::class);
        $this->eventFactory = $this->objectManager->get(\StripeIntegration\Payments\Test\Integration\Mock\Events\EventFactory::class);
        $this->quote = new \StripeIntegration\Payments\Test\Integration\Helper\Quote();
        $this->compare = new \StripeIntegration\Payments\Test\Integration\Helper\Compare($this);
        $this->paymentIntentModel = $this->objectManager->get(\StripeIntegration\Payments\Model\PaymentIntent::class);
    }

    /**
     * @magentoConfigFixture current_store payment/stripe_payments/active 1
     * @magentoConfigFixture current_store payment/stripe_payments_basic/stripe_mode test
     * @magentoConfigFixture current_store payment/stripe_payments_basic/stripe_test_pk pk_test_51Ig7MJHLyfDWKHBqqOpnyTkavM0LlpuH1QnrM1IsRGe26qwwo1uhQZbHyrnaiJuWpiIEkoFHgzgZoeLlfLOXp4ef00ApmFEugB
     * @magentoConfigFixture current_store payment/stripe_payments_basic/stripe_test_sk sk_test_51Ig7MJHLyfDWKHBqRZ6h9gRk1C738LWP1ljHVAyWsON7CIennpQV25sHvISdpbfHBqQWCNBTivTsKiIFjAPJhyB500ytiSiSSF
     * @magentoConfigFixture current_store payment/stripe_payments/checkout_mode 0
     * @magentoConfigFixture current_store payment/stripe_payments/payment_action authorize_capture
     *
     * @magentoDataFixture ../../../../app/code/StripeIntegration/Payments/Test/Integration/_files/Data/Taxes.php
     * @magentoDataFixture ../../../../app/code/StripeIntegration/Payments/Test/Integration/_files/Data/Addresses.php
     * @magentoDataFixture ../../../../app/code/StripeIntegration/Payments/Test/Integration/_files/Data/Products.php
     */
    public function testUpdateCart()
    {
        $this->quote->create()
            ->setCustomer('Guest')
            ->setCart("Normal")
            ->setShippingAddress("California")
            ->setShippingMethod("FlatRate")
            ->setBillingAddress("California")
            ->setPaymentMethod("DeclinedCard");

        $order = $this->quote->mockOrder();

        $exceptionMsg = $this->paymentIntentModel->confirmAndAssociateWithOrder($order, $order->getPayment());
        $this->assertEquals("Your card was declined.", $exceptionMsg);

        // We change the items in the cart and the shipping address and expect that
        // the cached Payment Intent will also be updated when we retry placing the order
        $this->quote->addProduct('simple-product', 2)
            ->setShippingAddress("NewYork")
            ->setShippingMethod("FlatRate")
            ->setPaymentMethod("SuccessCard");

        $order = $this->quote->placeOrder();
        $paymentIntentId = $order->getPayment()->getLastTransId();
        $paymentIntent = $this->stripeConfig->getStripeClient()->paymentIntents->retrieve($paymentIntentId);

        $grandTotal = $order->getGrandTotal() * 100;
        $orderIncrementId = $order->getIncrementId();

        $this->compare->object($paymentIntent, [
            "amount" => $grandTotal,
            "currency" => "usd",
            "amount_received" => $grandTotal,
            "description" => "Order #$orderIncrementId by Joyce Strother",
            "charges" => [
                "data" => [
                    0 => [
                        "amount" => $grandTotal,
                        "amount_captured" => $grandTotal,
                        "amount_refunded" => 0,
                        "metadata" => [
                            "Order #" => $orderIncrementId
                        ]
                    ]
                ]
            ],
            "metadata" => [
                "Order #" => $orderIncrementId
            ],
            "shipping" => [
                "address" => [
                    "city" => "New York",
                    "country" => "US",
                    "line1" => "1255 Duncan Avenue",
                    "postal_code" => "10013",
                    "state" => "New York",
                ],
                "name" => "Flint Jerry",
                "phone" => "917-535-4022"
            ],
            "status" => "succeeded"
        ]);
    }
}
