<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;

class Delete extends ProductController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $questionId = $this->getRequest()->getParam('id', false);
        try {
            $this->questionFactory->create()->setId($questionId)->delete();

            $this->messageManager->addSuccess(__('The question has been deleted.'));
            if ($this->getRequest()->getParam('ret') == 'pending') {
                $resultRedirect->setPath('question/*/pending');
            } else {
                $resultRedirect->setPath('question/*/');
            }
            return $resultRedirect;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong  deleting this question.'));
        }

        return $resultRedirect->setPath('question/*/edit/', ['id' => $questionId]);
    }
}
