<?php
namespace Custom\GoldPricing\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;

class Product
{
    protected $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function getProduct($product_id)
    {
        return $this->productRepository->getById($product_id);
    }
}
