<?php

namespace NDP\Question\Controller\Product;

use NDP\Question\Controller\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;
use NDP\Question\Model\Question;

class Post extends ProductController
{
    /**
     * Submit new question action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $data = $this->questionSession->getFormData(true);
        if (!$data) {
            $data = $this->getRequest()->getPostValue();
        }

        if (($product = $this->initProduct()) && !empty($data)) {

            /** @var \NDP\Question\Model\Question $question */
            $question = $this->questionFactory->create()->setData($data);
            $question->unsetData('question_id');

            $validate = $question->validate();
            if ($validate === true) {
                try {

                    $question->setProductId($product->getId())
                        ->setStatusId(Question::STATUS_PENDING)
                        ->setCustomerId($this->customerSession->getCustomerId())
                        ->setStoreId($this->storeManager->getStore()->getId())
                        ->save();

                    $this->messageManager->addSuccess(__('You submitted your question for moderation.'));
                } catch (\Exception $e) {
                    $this->questionSession->setFormData($data);
                    $this->messageManager->addError(__('We can\'t post your question right now.'));
                }
            } else {
                $this->questionSession->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $this->messageManager->addError($errorMessage);
                    }
                } else {
                    $this->messageManager->addError(__('We can\'t post your question right now.'));
                }
            }
        }

        $redirectUrl = $this->questionSession->getRedirectUrl(true);
        $resultRedirect->setUrl($redirectUrl ?: $this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }
}
