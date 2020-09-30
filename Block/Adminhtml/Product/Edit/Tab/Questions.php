<?php


namespace NDP\Question\Block\Adminhtml\Product\Edit\Tab;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Questions extends \NDP\Question\Block\Adminhtml\Grid
{
    /**
     * Hide grid mass action elements
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Determine ajax url for grid refresh
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('question/product_questions/grid', ['_current' => true]);
    }
}
