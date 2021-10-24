<?php
namespace Custom\Merchant\Block\Adminhtml\CustomerEdit\Tab;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
/**
 * Customer account form block
 */
class View extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    protected $customerRepository;
    protected $customerEdit;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Webkul\Marketplace\Block\Adminhtml\Customer\Edit $customerEdit,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_systemStore = $systemStore;
        $this->customerRepository = $customerRepository;
        $this->customerEdit = $customerEdit;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Allowed Products');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Allowed Products');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        $coll = $this->customerEdit->getMarketplaceUserCollection();
        $isSeller = false;
        foreach ($coll as $row) {
            $isSeller = $row->getIsSeller();
        }
        if ($this->getCustomerId() && $isSeller) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('myform_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Limit Number Of Seller Products Creation')]);
        $customer = $this->customerRepository->getById($this->getCustomerId());

        $fieldset->addField(
            'number_of_products',
            'text',
            [
                'name' => 'customer[number_of_products]',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('Allowed Number Of Products'),
                'title' => __('Allowed Number Of Products'),
                'value' => !empty($customer->getCustomAttribute('number_of_products')) ? $customer->getCustomAttribute('number_of_products')->getValue() : 0
            ]
        );
        $this->setForm($form);
        return $this;
    }
    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
    /**
     * Prepare the layout.
     *
     * @return $this
     */
// You can call other Block also by using this function if you want to add phtml file.
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(
            \Webkul\Marketplace\Block\Adminhtml\Customer\Edit\Js::class
        )->toHtml();
        return $html;
    }
}
