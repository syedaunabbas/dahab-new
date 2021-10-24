<?php
/**
 * @package  Custom\FeaturedCategories
 * @author Aun Abbas <syedaun.abbasrizvi@gmail.com>
 */

namespace Custom\FeaturedCategories\Api;

interface ConfigResolverInterface
{
    /**
     * Configuration paths
     */
    const PATH_ENABLE = 'featuredcategories/general/enable';

    /**
     * @return bool
     */
    public function getIsEnable(): bool;
}
