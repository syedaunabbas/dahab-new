<?php

namespace Custom\FeaturedProducts\Cron;

class Product
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $_OrderCollection;
    protected $productRepository;
    protected $orderRepository;

    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Sales\Model\ResourceModel\Order\Collection $_OrderCollection
    ) {
        $this->logger = $loggerInterface;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->_OrderCollection = $_OrderCollection;
    }

    public function execute()
    {
        $collection = $this->_OrderCollection;
        $collection
            ->addFieldToSelect(['entity_id','featured_expire_date','is_featured_completed'])
            ->addFieldToFilter('is_featured_completed', array('eq' => 'active'))
            ->addFieldToFilter('featured_expire_date', array('lteq' => date('d:m:Y h:i:s')));

        foreach ($collection->getData() as $order){
            $order = $this->orderRepository->get($order['entity_id']);
            foreach($order->getItems() as $item){
                foreach ($item->getData('product_options')['options'] as $option){
                    if($option['option_id'] == 2){
                        $this->logger->info(print_r(json_encode($option),true));
                        $skus = explode(',',$option['option_value']);
                        foreach ($skus as $sku) {
                            $product = $this->productRepository->get($sku);
                            $product->setData('is_featured', 0);
                            $product->save();
                        }
                    }
                }
            }
            $order->setData('is_featured_completed','inactive');
            $order->save();
        }

        return $this;
    }
}
