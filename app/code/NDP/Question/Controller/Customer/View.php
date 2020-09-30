<?php

namespace NDP\Question\Controller\Customer;

use NDP\Question\Controller\Customer as CustomerController;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use NDP\Question\Model\QuestionFactory;
use Magento\Framework\Controller\ResultFactory;

class View extends CustomerController
{
    /**
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $questionFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        QuestionFactory $questionFactory
    ) {
        $this->questionFactory = $questionFactory;
        parent::__construct($context, $customerSession);
    }
    /**
     * Render question details
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $question = $this->questionFactory->create()->load($this->getRequest()->getParam('id'));
        if ($question->getCustomerId() != $this->customerSession->getCustomerId()) {
            /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultForward->forward('noroute');
            return $resultForward;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('question/customer');
        }
        $resultPage->getConfig()->getTitle()->set(__('Question Details'));
        return $resultPage;
    }
}
