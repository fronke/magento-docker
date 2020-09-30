<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends ProductController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (($data = $this->getRequest()->getPostValue()) && ($questionId = $this->getRequest()->getParam('id'))) {
            $question = $this->questionFactory->create()->load($questionId);
            if (!$question->getId()) {
                $this->messageManager->addError(__('The question was removed by another user or does not exist.'));
            } else {
                try {
                    $questionAnswered = false;
                    if ($question->getAnswerContent() != $data['answer_content']) {
                        $questionAnswered = true;
                    }

                    $question->addData($data)->save();

                    if ($questionAnswered) {
                        $this->_eventManager->dispatch('question_answered',['customer_id'=>$question->getCustomerId()]);
                    }

                    $this->messageManager->addSuccess(__('You saved the question.'));
                } catch (LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addException($e, __('Something went wrong while saving this question.'));
                }
            }

            $nextId = (int)$this->getRequest()->getParam('next_item');
            if ($nextId) {
                $resultRedirect->setPath('question/*/edit', ['id' => $nextId]);
            } elseif ($this->getRequest()->getParam('ret') == 'pending') {
                $resultRedirect->setPath('*/*/pending');
            } else {
                $resultRedirect->setPath('*/*/');
            }
            return $resultRedirect;
        }
        $resultRedirect->setPath('question/*/');
        return $resultRedirect;
    }
}
