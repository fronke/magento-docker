<?php

namespace NDP\Question\Block\Form;


class Configure extends \NDP\Question\Block\Form
{
    /**
     * Get question product id
     *
     * @return int
     */
    public function getProductId()
    {
        return (int)$this->getRequest()->getParam('product_id', false);
    }
}
