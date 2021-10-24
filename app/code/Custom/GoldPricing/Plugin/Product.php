<?php

namespace Custom\GoldPricing\Plugin;

use Custom\GoldPricing\Helper\PriceCalculation;

class Product
{
    protected $priceCalculation;

    public function __construct(
        PriceCalculation $priceCalculation
    ){
        $this->priceCalculation = $priceCalculation;
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        return $this->priceCalculation->convertPriceToGoldPrice($subject,$result);
    }
}
