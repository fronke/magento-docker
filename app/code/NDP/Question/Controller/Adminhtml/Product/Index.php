<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;

class Index extends ProductController
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('ajax')) {
            /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultForward->forward('questionGrid');
            return $resultForward;
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('NDP_Question::catalog_questions_ratings_questions_all');
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Questions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Questions'));
        $resultPage->addContent($resultPage->getLayout()->createBlock('NDP\Question\Block\Adminhtml\Main'));
        return $resultPage;
    }
}
