<?php

namespace Custom\FeaturedCategories\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Custom\FeaturedCategories\Api\ConfigResolverInterface;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    /**
     * @var ConfigResolverInterface
     */
    protected $configResolver;


    /**
     * @param Context $context
     * @param ConfigResolverInterface $configResolver
     */
    public function __construct(
        Context $context,
        ConfigResolverInterface $configResolver
    ) {
        parent::__construct($context);
        $this->configResolver = $configResolver;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->configResolver->getIsEnable();
    }
}
