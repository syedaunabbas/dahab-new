<?php
/**
 * @package  Custom\FeaturedProducts
 * @author Aun Abbas <syedaun.abbasrizvi@gmail.com>
 */

namespace Custom\FeaturedProducts\Api;

interface ConfigResolverInterface
{
    /**
     * Configuration paths
     */
    const PATH_ENABLE = 'featuredproducts/general/enable';

    /**
     * @return bool
     */
    public function getIsEnable(): bool;
}
