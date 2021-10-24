<?php

namespace Custom\GoldPricing\Block;

class GoldRates extends \Magento\Framework\View\Element\Template
{
    public $helper;

    public $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Custom\GoldPricing\Helper\PriceCalculation $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getConfigValue($value)
    {
        return $this->helper->getConfigValue($value);
    }

    public function getCurrencySymbol() {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }

}
