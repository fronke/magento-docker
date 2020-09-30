<?php

namespace NDP\Question\Controller\Product;

use Magento\Framework\Exception\LocalizedException;
use NDP\Question\Controller\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;

class ListAjax extends ProductController
{

    public function execute()
    {
        if (!$this->initProduct()) {
            return '';
            //throw new LocalizedException(__('Cannot initialize product'));
        } else {
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        }

        return $resultLayout;
    }
}
