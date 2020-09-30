<?php

namespace NDP\Question\Observer;

use Magento\Framework\Event\ObserverInterface;

class CatalogBlockProductCollectionBeforeToHtmlObserver implements ObserverInterface
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
     * Append question summary before rendering html
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $productCollection = $observer->getEvent()->getCollection();
        if ($productCollection instanceof \Magento\Framework\Data\Collection) {
            $productCollection->load();
            //$this->_questionFactory->create()->appendSummary($productCollection);
        }

        return $this;
    }
}
