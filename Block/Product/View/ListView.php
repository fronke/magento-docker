<?php

namespace NDP\Question\Block\Product\View;

class ListView extends \NDP\Question\Block\Product\View
{
    /**
     * Unused class property
     * @var false
     */
    protected $_forceHasOptions = false;

    protected $_currentPage = 1;

    /**
     * Get product id
     *
     * @return int|null
     */
    public function getProductId()
    {
        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * Prepare product question list toolbar
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $toolbar = $this->getLayout()->getBlock('product_question_list.toolbar');
        if ($toolbar) {
            $this->_currentPage = $toolbar->getCurrentPage();
            $toolbar->setLimit(5);
            $toolbar->setCollection($this->getQuestionsCollection());
            $this->setChild('toolbar', $toolbar);
        }

        return $this;
    }

    /**
     * Add rate votes
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->getQuestionsCollection()->load();
        return parent::_beforeToHtml();
    }

    /**
     * Return question url
     *
     * @param int $id
     * @return string
     */
    public function getQuestionUrl($id)
    {
        return $this->getUrl('*/*/view', ['id' => $id]);
    }

    public function getCurrentPage()
    {
        return $this->_currentPage;
    }
}
