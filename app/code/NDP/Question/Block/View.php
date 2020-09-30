<?php

namespace NDP\Question\Block;

class View extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * View template name
     *
     * @var string
     */
    protected $_template = 'view.phtml';


    /**
     * Question model
     *
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $_questionFactory;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \NDP\Question\Model\QuestionFactory $questionFactory,
        array $data = []
    ) {
        $this->_questionFactory = $questionFactory;

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Retrieve current product model from registry
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProductData()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * Retrieve current question model from registry
     *
     * @return \NDP\Question\Model\Question
     */
    public function getQuestionData()
    {
        return $this->_coreRegistry->registry('current_question');
    }

    /**
     * Prepare link to question list for current product
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/list', ['id' => $this->getProductData()->getId()]);
    }


    /**
     * Retrieve total question count for current product
     *
     * @return string
     */
    public function getTotalQuestions()
    {
        if (!$this->getTotalQuestionsCache()) {
            $this->setTotalQuestionsCache(
                $this->_questionFactory->create()->getTotalQuestions(
                    $this->getProductData()->getId(),
                    false,
                    null //$this->_storeManager->getStore()->getId()
                )
            );
        }
        return $this->getTotalQuestionsCache();
    }

    /**
     * Format date in long format
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::LONG);
    }
}
