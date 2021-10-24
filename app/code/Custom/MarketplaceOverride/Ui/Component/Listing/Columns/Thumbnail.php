<?php

namespace Custom\MarketplaceOverride\Ui\Component\Listing\Columns;

class Thumbnail extends \Webkul\Marketplace\Ui\Component\Listing\Columns\Thumbnail
{

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $store = $this->storeManager->getStore(
            $this->context->getFilterParam(
                'store_id',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            )
        );
        $currency = $this->localeCurrency->getCurrency($store->getBaseCurrencyCode());
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $product = $this->productModel->create()->load($item['mageproduct_id']);
                //$product = new \Magento\Framework\DataObject($item);
                $imageHelper = $this->imageHelper->init($product, 'product_listing_thumbnail');
                $imageUrl = $imageHelper->getUrl();
                $item[$fieldName . '_src'] = $imageUrl;
                $item[$fieldName . '_alt'] = $imageHelper->getLabel();
                $origImageHelper = $this->imageHelper->init(
                    $product,
                    'product_listing_thumbnail_preview'
                );
                $item[$fieldName . '_orig_src'] = $origImageHelper->getUrl();
                $item[$fieldName . '_name'] = $product->getName();

                // Hide price tag having issue for bundle products
                /*if ($product->getPrice() * 1) {
                    $price = $product->getFormatedPrice();
                } else {
                    $price = $currency->toCurrency(sprintf('%f', $product->getPrice()));
                }

                $item[$fieldName . '_price'] = __('Price') . ' - ' . strip_tags(html_entity_decode($price));*/
                $item[$fieldName . '_description'] = strip_tags(
                    html_entity_decode($product->getDescription())
                );
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'catalog/product/edit',
                    ['id' => $item['mageproduct_id'], 'store' => 0]
                );
            }
        }

        return $dataSource;
    }
}
