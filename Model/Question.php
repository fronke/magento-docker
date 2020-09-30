<?php

namespace NDP\Question\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject\IdentityInterface;
use NDP\Question\Model\ResourceModel\Question\Product\Collection as ProductCollection;

class Question extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    /**
     * Event prefix for observer
     *
     * @var string
     */
    protected $_eventPrefix = 'question';

    /**
     * Cache tag
     */
    const CACHE_TAG = 'question_block';


    /**
     * Approved question status code
     */
    const STATUS_PUBLIC = 0;

    /**
     * Pending question status code
     */
    const STATUS_PRIVATE = 1;

    /**
     * Pending question status code
     */
    const STATUS_PENDING = 2;


    /**
     * Question product collection factory
     *
     * @var \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    protected $customer;


    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Url interface
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlModel;

    protected $customerFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory $productFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlModel,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productCollectionFactory = $productFactory;
        $this->_storeManager = $storeManager;
        $this->_urlModel = $urlModel;
        $this->customerFactory = $customerFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('NDP\Question\Model\ResourceModel\Question');
    }

    /**
     * Get product collection
     *
     * @return ProductCollection
     */
    public function getProductCollection()
    {
        return $this->productCollectionFactory->create();
    }


    /**
     * Get total questions
     *
     * @param bool $approvedOnly
     * @param int $storeId
     * @return int
     */
    public function getTotalQuestions($approvedOnly = false, $storeId = 0)
    {
        return $this->getResource()->getTotalQuestions($approvedOnly, $storeId);
    }



    /**
     * Get question product view url
     *
     * @return string
     */
    public function getQuestionUrl()
    {
        return $this->_urlModel->getUrl('question/product/view', ['id' => $this->getQuestionId()]);
    }

    /**
     * Get product view url
     *
     * @param string|int $productId
     * @param string|int $storeId
     * @return string
     */
    public function getProductUrl($productId, $storeId)
    {
        if ($storeId) {
            $this->_urlModel->setScope($storeId);
        }

        return $this->_urlModel->getUrl('catalog/product/view', ['id' => $productId]);
    }

    /**
     * Validate question summary fields
     *
     * @return bool|string[]
     */
    public function validate()
    {
        $errors = [];

        if (!\Zend_Validate::is($this->getQuestionContent(), 'NotEmpty')) {
            $errors[] = __('Please enter a question.');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    /**
     * Check if current question available on passed store
     *
     * @param int|\Magento\Store\Model\Store $store
     * @return bool
     */
    public function isAvailableOnStore($store = null)
    {
        $store = $this->_storeManager->getStore($store);
        if ($store) {
            return in_array($store->getId(), (array) $this->getStores());
        }
        return false;
    }


    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        $tags = [];
        if ($this->getProductId()) {
            $tags[] = Product::CACHE_TAG . '_' . $this->getProductId();
        }
        return $tags;
    }

    public function getCustomer() {
        if (!$this->customer) {
            if ($this->getCustomerId() != null) {
                $this->customer = $this->customerFactory->create()->load($this->getCustomerId());
            }
            else {
                return null;
            }
        }
       return $this->customer;
    }



}
