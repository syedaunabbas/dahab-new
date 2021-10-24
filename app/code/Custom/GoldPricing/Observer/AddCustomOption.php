<?php

namespace Custom\GoldPricing\Observer;

use Magento\Framework\Event\ObserverInterface;

class AddCustomOption implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        $product->setData('weight', $product->getData('product_weight_gm') / 1000);
        $product->getResource()->saveAttribute($product, 'weight');
    }
}
