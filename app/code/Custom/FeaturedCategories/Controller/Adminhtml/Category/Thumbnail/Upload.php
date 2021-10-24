<?php
namespace Custom\FeaturedCategories\Controller\Adminhtml\Category\Thumbnail;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class Upload
 * @package Custom\FeaturedCategories\Controller\Adminhtml\Category\Thumbnail
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Image uploader
     *
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Media directory object (writable).
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->imageUploader = $imageUploader;
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->logger = $logger;
    }

    /**
     * Upload thumbnail controller action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('thumbnail');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
