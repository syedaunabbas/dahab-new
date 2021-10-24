<?php

namespace Custom\FeaturedCategories\Block;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Custom\FeaturedCategories\Helper\Data as CustomHelper;

class FeaturedCategories extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_categoryCollectionFactory;

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
        CollectionFactory                                $categoryCollectionFactory,
        CustomHelper                                     $customHelper,
        \Magento\Store\Model\StoreManagerInterface       $storeManager,
        array                                            $data = []
    )
    {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_customHelper = $customHelper;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        if (!$this->_customHelper->isModuleEnabled()) {
            return [];
        }

        $collection = $this->_categoryCollectionFactory->create()->setStore($this->_storeManager->getStore()->getId());
        $collection->addAttributeToSelect('*');

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        // select categories of certain level
        if ($level) {
            $collection->addAttributeToFilter('level', $level);
        }

        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }

        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }

        // select categories not is featured
        $collection->addAttributeToFilter('is_featured', 0);

        #print_r($collection->getSelectSql(true));die;

        return $collection;
    }

    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

}
