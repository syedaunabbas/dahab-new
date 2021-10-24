<?php

namespace Custom\FeaturedProducts\Block;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Custom\FeaturedProducts\Helper\Data as CustomHelper;

class FeaturedProducts extends \Magento\Framework\View\Element\Template
{

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_productCollectionFactory;

    /**
     * @var CustomHelper
     */
    protected $_customHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory                                $productCollectionFactory,
        CustomHelper                                     $customHelper,
        \Magento\Store\Model\StoreManagerInterface       $storeManager,
        array                                            $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_customHelper = $customHelper;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * get collection of feature products
     * @return mixed
     */
    public function getProductCollection()
    {
        if (!$this->_customHelper->isModuleEnabled()) {
            return [];
        }

        $collection = $this->_productCollectionFactory->create()->setStore($this->_storeManager->getStore()->getId());
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_featured', '1');
        return $collection;
    }

    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }
}

