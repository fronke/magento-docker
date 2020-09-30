<?php

namespace NDP\Question\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Filter manager
     *
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filter;

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Filter\FilterManager $filter
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Filter\FilterManager $filter
    ) {
        $this->_escaper = $escaper;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * Return short detail info in HTML
     *
     * @param string $origDetail Full detail info
     * @return string
     */
    public function getAnswerContentHtml($origDetail)
    {
        return $this->filter->truncate($this->_escaper->escapeHtml($origDetail), ['length' => 50]);
    }


    /**
     * Get question statuses with their codes
     *
     * @return array
     */
    public function getQuestionStatuses()
    {
        return \NDP\Question\Model\Question\Status::getOptionArray();
    }

    /**
     * Get question statuses as option array
     *
     * @return array
     */
    public function getQuestionStatusesOptionArray()
    {
        $result = [];
        foreach ($this->getQuestionStatuses() as $value => $label) {
            $result[] = ['value' => $value, 'label' => $label];
        }

        return $result;
    }
}
