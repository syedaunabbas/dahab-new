<?php

namespace Custom\FeaturedProducts\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterPlaceOrder implements ObserverInterface

{
    protected $productRepository;
    protected $order;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\Order $order,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->logger = $logger;
        $this->order = $order;
        $this->productRepository = $productRepository;
    }


    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {

        $orderId = $observer->getEvent()->getOrderIds();
        $order = $this->order->load($orderId);
        //get Order All Item
        $itemCollection = $order->getItemsCollection();

        foreach ($itemCollection as $item){
            if($item->getData('product_type') == 'virtual'){
                $order->setData('is_featured_completed','active');
                foreach ($item->getData('product_options')['options'] as $option){
                    if($option['option_id'] == 1){
                        $order->setData('featured_expire_date',date('d:m:Y h:i:s', strtotime( '+'.$option['value'] .'day')));
                    }
                    if($option['option_id'] == 2){
                        $skus = explode(',',$option['option_value']);
                        foreach ($skus as $sku) {
                            $product = $this->productRepository->get($sku);
                            $product->setData('is_featured', 1);
                            $product->save();
                        }
                    }
                }
            }
        }
        $order->save();
    }

}
