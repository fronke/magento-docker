<?php

namespace NDP\Question\Observer;

use Magento\Framework\Event\ObserverInterface;

class TagProductCollectionLoadAfterObserver implements ObserverInterface
{
    /**
     * Question model
     *
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $_questionFactory;

    /**
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     */
    public function __construct(
        \NDP\Question\Model\QuestionFactory $questionFactory
    ) {
        $this->_questionFactory = $questionFactory;
    }

    /**
     * Add question summary info for tagged product collection
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();
        //$this->_questionFactory->create()->appendSummary($collection);

        return $this;
    }
}
