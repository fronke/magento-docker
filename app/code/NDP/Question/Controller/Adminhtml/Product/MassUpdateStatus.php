<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;

class MassUpdateStatus extends ProductController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $questionsIds = $this->getRequest()->getParam('questions');
        if (!is_array($questionsIds)) {
            $this->messageManager->addError(__('Please select question(s).'));
        } else {
            try {
                $status = $this->getRequest()->getParam('status');
                foreach ($questionsIds as $questionId) {
                    $model = $this->questionFactory->create()->load($questionId);
                    $model->setStatusId($status)->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been updated.', count($questionsIds))
                );
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while updating these question(s).')
                );
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('question/*/' . $this->getRequest()->getParam('ret', 'index'));
        return $resultRedirect;
    }
}
