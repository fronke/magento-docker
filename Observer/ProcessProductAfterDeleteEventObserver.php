<?php

namespace NDP\Question\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProcessProductAfterDeleteEventObserver implements ObserverInterface
{
    /**
     * Question resource model
     *
     * @var \NDP\Question\Model\ResourceModel\Question
     */
    protected $_resourceQuestion;

    /**
     * @param \NDP\Question\Model\ResourceModel\Question $resourceQuestion
     */
    public function __construct(
        \NDP\Question\Model\ResourceModel\Question $resourceQuestion
    ) {
        $this->_resourceQuestion = $resourceQuestion;
    }

    /**
     * Cleanup product questions after product delete
     *
     * @param   \Magento\Framework\Event\Observer $observer
     * @return  $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $eventProduct = $observer->getEvent()->getProduct();
        if ($eventProduct && $eventProduct->getId()) {
            $this->_resourceQuestion->deleteQuestionsByProductId($eventProduct->getId());
        }

        return $this;
    }
}
