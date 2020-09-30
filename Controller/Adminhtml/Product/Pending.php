<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;

class Pending extends ProductController
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('ajax')) {
            $this->coreRegistry->register('usePendingFilter', true);
            /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultForward->forward('questionGrid');
            return $resultForward;
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Questions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Pending Questions'));
        $this->coreRegistry->register('usePendingFilter', true);
        $resultPage->addContent($resultPage->getLayout()->createBlock('NDP\Question\Block\Adminhtml\Main'));
        return $resultPage;
    }
}
