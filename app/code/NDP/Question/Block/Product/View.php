<?php

namespace NDP\Question\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use NDP\Question\Model\ResourceModel\Question\Collection as QuestionCollection;

class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * Question collection
     *
     * @var QuestionCollection
     */
    protected $_questionsCollection;

    /**
     * Question resource model
     *
     * @var \NDP\Question\Model\ResourceModel\Question\CollectionFactory
     */
    protected $_questionsColFactory;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \NDP\Question\Model\ResourceModel\Question\CollectionFactory $collectionFactory
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \NDP\Question\Model\ResourceModel\Question\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_questionsColFactory = $collectionFactory;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }


    /**
     * Get collection of questions
     *
     * @return QuestionCollection
     */
    public function getQuestionsCollection()
    {
        if (null === $this->_questionsCollection) {
            $this->_questionsCollection = $this->_questionsColFactory->create()
                ->addStatusFilter(
                \NDP\Question\Model\Question::STATUS_PUBLIC
            )->addEntityFilter(
                $this->getProduct()->getId()
            )->setDateOrder();
       }
        return $this->_questionsCollection;
    }

    /**
     * Force product view page behave like without options
     *
     * @return bool
     */
    public function hasOptions()
    {
        return false;
    }
}
