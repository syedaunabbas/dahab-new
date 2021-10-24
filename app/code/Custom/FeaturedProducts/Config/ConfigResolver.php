<?php
/**
 * @package  Custom\FeaturedProducts
 * @author Aun Abbas <syedaun.abbasrizvi@gmail.com>
 */

namespace Custom\FeaturedProducts\Config;

use Custom\FeaturedProducts\Api\ConfigResolverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigResolver implements ConfigResolverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigResolver constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function getIsEnable(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::PATH_ENABLE, ScopeInterface::SCOPE_STORE);
    }
}
