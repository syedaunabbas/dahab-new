<?php
namespace Custom\FeaturedCategories\Model\Catalog\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
    /**
     * @return array
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'thumbnail';

        return $fields;
    }
}
