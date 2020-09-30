<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;

class Edit extends ProductController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('NDP_Question::catalog_questions_ratings_questions_all');
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Questions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Question'));
        $resultPage->addContent($resultPage->getLayout()->createBlock('NDP\Question\Block\Adminhtml\Edit'));
        return $resultPage;
    }
}
