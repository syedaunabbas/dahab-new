<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Custom\GoldPricing\Helper;

use Custom\GoldPricing\Model\Product as ProductInfo;

class PriceCalculation
{
    protected $productInfo;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ProductInfo $productInfo
    ){
        $this->productInfo = $productInfo;
        $this->_scopeConfig = $scopeConfig;
    }

    public function getConfigValue($configKey)
    {
        return $this->_scopeConfig->getValue($configKey, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function goldPriceFormulaCalculation($purity_price,$gram_of_gold,$product_price){
        return $purity_price + ($product_price * (double) $gram_of_gold);
    }

    public function convertPriceToGoldPrice($product, $price)
    {
        $gold_pricing_module_enable = $this->getConfigValue('goldpricing/general/enable');

        if($product->getAttributeText('gold_product_type') == 'Yes' && $gold_pricing_module_enable){
            $purity_price = $this->getGoldPurityRate($product->getAttributeText('gold_purity'));
            $gram_of_gold = $product->getData('product_weight_gm');
            return $this->goldPriceFormulaCalculation($purity_price,$gram_of_gold,$product->getData('price'));
        }

        return $price;
    }

    public function getGoldPurityRate($goldCaret){
        $caret_value = null;

        switch ((string)$goldCaret){
            case 'Ounce':
                $caret_value = 'goldpricing/general/ounce_gold_rate';
                break;
            case '14 Caret':
                $caret_value = 'goldpricing/general/14_carat_gold_rate';
                break;
            case '18 Caret':
                $caret_value = 'goldpricing/general/18_carat_gold_rate';
                break;
            case '20 Caret':
                $caret_value = 'goldpricing/general/20_carat_gold_rate';
                break;
            case '21 Caret':
                $caret_value = 'goldpricing/general/21_carat_gold_rate';
                break;
            case '24 Caret':
                $caret_value = 'goldpricing/general/24_carat_gold_rate';
                break;
            default:
                $caret_value = 'goldpricing/general/21_carat_gold_rate';
        }

        return $this->getConfigValue($caret_value);
    }

}
