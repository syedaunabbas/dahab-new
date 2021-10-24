<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Custom\GoldPricing\Model;

use Custom\GoldPricing\Model\Product as ProductInfo;
use Custom\GoldPricing\Helper\PriceCalculation;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    protected $priceCalculation;
    protected $productInfo;

    public function __construct(
        PriceCalculation $priceCalculation,
        ProductInfo $productInfo
    ){
        $this->priceCalculation = $priceCalculation;
        $this->productInfo = $productInfo;
    }

    public function getFinalPrice($qty, $product)
    {
        $product = $this->productInfo->getProduct($product->getId());
        $price = $product->getData('price');

        return $this->priceCalculation->convertPriceToGoldPrice($product,$price);
    }

}
