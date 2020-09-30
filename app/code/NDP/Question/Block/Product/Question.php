<?php

namespace NDP\Question\Block\Product;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;


class Question extends Template implements IdentityInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Question resource model
     *
     * @var \NDP\Question\Model\ResourceModel\Question\CollectionFactory
     */
    protected $_questionsColFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \NDP\Question\Model\ResourceModel\Question\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \NDP\Question\Model\ResourceModel\Question\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_questionsColFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get current product id
     *
     * @return null|int
     */
    public function getProductId()
    {
        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * Get URL for ajax call
     *
     * @return string
     */
    public function getProductQuestionUrl()
    {
        return $this->getUrl(
            'question/product/listAjax',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
    }


    /**
     * Get size of questions collection
     *
     * @return int
     */
    public function getCollectionSize()
    {
        $collection = $this->_questionsColFactory->create()->addStatusFilter(
            \NDP\Question\Model\Question::STATUS_PUBLIC
        )->addEntityFilter(
            $this->getProductId()
        );

        return $collection->getSize();
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\NDP\Question\Model\Question::CACHE_TAG];
    }
}
