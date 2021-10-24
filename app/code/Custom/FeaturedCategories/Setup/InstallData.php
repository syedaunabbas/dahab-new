<?php

namespace Custom\FeaturedCategories\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var CategorySetupFactory
     */
    protected $categorySetupFactory;

    /**
     * UpgradeSchema constructor.
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory
    )
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();


        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $categorySetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'thumbnail');

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'thumbnail', [
                'type' => 'varchar',
                'label' => 'Thumbnail',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 5,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );


        $installer->endSetup();
    }
}
