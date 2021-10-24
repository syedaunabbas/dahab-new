<?php

namespace Custom\GoldPricing\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'gold_product_type');
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'gold_product_type', [
            'group' => 'Product Details',
            'type' => 'int',
            'sort_order' => 200,
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'label' => 'Product Type (Is gold)',
            'input' => 'boolean',
            'class' => '',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => ''
        ]);

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'gold_purity');
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'gold_purity', [
            'group' => 'Product Details',
            'type' => 'varchar',
            'backend' => '',
            'frontend' => '',
            'sort_order' => 210,
            'label' => 'Gold Purity',
            'input' => 'select',
            'class' => '',
            'source' => 'Custom\GoldPricing\Model\Source\PurityOption',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'apply_to' => ''
        ]);

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_weight_gm');
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_weight_gm', [
            'group' => 'Product Details',
            'type' => 'text',
            'backend' => '',
            'frontend' => '',
            'sort_order' => 220,
            'label' => 'Product weight in gram',
            'input' => 'text',
            'class' => '',
            'source' => '',
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '0',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => ''
        ]);

    }
}
