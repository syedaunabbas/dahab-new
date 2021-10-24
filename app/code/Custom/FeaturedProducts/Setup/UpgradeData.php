<?php

namespace Custom\FeaturedProducts\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Sales\Setup\SalesSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CategorySetupFactory
     */
    protected $categorySetupFactory;

    private $salesSetupFactory;

    /**
     * UpgradeSchema constructor.
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory,
        SalesSetupFactory    $salesSetupFactory
    )
    {
        $this->categorySetupFactory = $categorySetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion() && version_compare($context->getVersion(), '1.1.1') < 0) {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $categorySetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'is_featured',
                [
                    'type' => 'int',
                    'label' => 'Is Featured',
                    'input' => 'select',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'default' => '0',
                    'sort_order' => 40,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                ]
            );
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.1.2') < 0) {
            $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
            $salesSetup->addAttribute(
                'order',
                'is_featured_completed',
                [
                    'type' => 'varchar',
                    'visible' => true,
                    'required' => false,
                    'grid' => true
                ]
            );

            $salesSetup->addAttribute(
                'order',
                'featured_expire_date',
                [
                    'type' => 'varchar',
                    'visible' => true,
                    'required' => false,
                    'grid' => true
                ]
            );
        }
        $setup->endSetup();
    }
}
