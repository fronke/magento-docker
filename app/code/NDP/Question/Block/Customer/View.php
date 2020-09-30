<?php

namespace NDP\Question\Block\Customer;

use Magento\Catalog\Model\Product;
use NDP\Question\Model\ResourceModel\Rating\Option\Vote\Collection as VoteCollection;
use NDP\Question\Model\Question;

/**
 * Customer Question detailed view block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class View extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * Customer view template name
     *
     * @var string
     */
    protected $_template = 'customer/view.phtml';

    /**
     * Catalog product model
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Question model
     *
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $_questionFactory;


    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \NDP\Question\Model\QuestionFactory $questionFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->_questionFactory = $questionFactory;
        $this->currentCustomer = $currentCustomer;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Initialize question id
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setQuestionId($this->getRequest()->getParam('id', false));
    }

    /**
     * Get product data
     *
     * @return Product
     */
    public function getProductData()
    {
        if ($this->getQuestionId() && !$this->getProductCacheData()) {
            $product = $this->productRepository->getById($this->getQuestionData()->getProductId());
            $this->setProductCacheData($product);
        }
        return $this->getProductCacheData();
    }

    /**
     * Get question data
     *
     * @return Question
     */
    public function getQuestionData()
    {
        if ($this->getQuestionId() && !$this->getQuestionCachedData()) {
            $this->setQuestionCachedData($this->_questionFactory->create()->load($this->getQuestionId()));
        }
        return $this->getQuestionCachedData();
    }

    /**
     * Return question customer url
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('question/customer');
    }


    /**
     * Get total questions
     *
     * @return int
     */
    public function getTotalQuestions()
    {
        if (!$this->getTotalQuestionsCache()) {
            $this->setTotalQuestionsCache(
                $this->_questionFactory->create()->getTotalQuestions($this->getProductData()->getId()),
                false,
                null //$this->_storeManager->getStore()->getId()
            );
        }
        return $this->getTotalQuestionsCache();
    }

    /**
     * Get formatted date
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::LONG);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->currentCustomer->getCustomerId() ? parent::_toHtml() : '';
    }
}
