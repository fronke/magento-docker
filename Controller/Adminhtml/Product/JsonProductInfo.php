<?php

namespace NDP\Question\Controller\Adminhtml\Product;

use NDP\Question\Controller\Adminhtml\Product as ProductController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use NDP\Question\Model\QuestionFactory;
use NDP\Question\Model\RatingFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Controller\ResultFactory;

class JsonProductInfo extends ProductController
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        QuestionFactory $questionFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
        parent::__construct($context, $coreRegistry, $questionFactory);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $response = new DataObject();
        $id = $this->getRequest()->getParam('id');
        if (intval($id) > 0) {
            $product = $this->productRepository->getById($id);
            $response->setId($id);
            $response->addData($product->getData());
            $response->setError(0);
        } else {
            $response->setError(1);
            $response->setMessage(__('We can\'t retrieve the product ID.'));
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($response->toArray());
        return $resultJson;
    }
}
