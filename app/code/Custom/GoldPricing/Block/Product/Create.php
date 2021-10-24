<?php
namespace Custom\GoldPricing\Block\Product;

class Create extends \Webkul\Marketplace\Block\Product\Create
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate("Custom_GoldPricing::product/add.phtml");
        return $this;
    }
}
