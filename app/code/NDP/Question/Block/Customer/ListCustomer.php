<?php

namespace NDP\Question\Block\Customer;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Questions list block
 */
class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
{
    protected $_defaultToolbarBlock = 'NDP\Airsoft\Block\ListItems\Toolbar';

    /**
     * Product questions collection
     *
     * @var \NDP\Question\Model\ResourceModel\Question\Product\Collection
     */
    protected $_collection;

    /**
     * Question resource model
     *
     * @var \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory $collectionFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Get html code for toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        if ($this->getQuestions()) {
            $toolbar = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'customer_question_list.toolbar'
            )->setCollection(
                $this->getQuestions()
            );

            $this->setChild('toolbar', $toolbar);
        }
        return parent::_prepareLayout();
    }

    /**
     * Get questions
     *
     * @return bool|\NDP\Question\Model\ResourceModel\Question\Product\Collection
     */
    public function getQuestions()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return false;
        }
        if (!$this->_collection) {
            $this->_collection = $this->_collectionFactory->create();
            $this->_collection
//              ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addCustomerFilter($customerId);
        }
        return $this->_collection;
    }

    /**
     * Get question link
     *
     * @return string
     */
    public function getQuestionLink()
    {
        return $this->getUrl('question/customer/view/');
    }

    /**
     * Get product link
     *
     * @return string
     */
    public function getProductLink()
    {
        return $this->getUrl('catalog/product/view/');
    }

    /**
     * Format date in short format
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::SHORT);
    }

    /**
     * Add question summary
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getQuestions();

        // use sortable parameters
        $orders = ['created_at' => __('Date'), 'rt.answer_content' => __('Answered'), 'rt.status_id' => __('Status'), 'rt.priority' => __('Priority')];

        $toolbar->setAvailableOrders($orders);
        $toolbar->setDefaultOrder('created_at');

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->getQuestions()->load();

        return parent::_beforeToHtml();
    }


    public function getToolbarBlock()
    {
        $block = $this->getLayout()->getBlock('questions_list_toolbar');
        if ($block) {
            return $block;
        }
    }
}
