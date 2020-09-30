<?php

namespace NDP\Question\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use NDP\Question\Model\QuestionFactory;
use NDP\Question\Model\RatingFactory;

/**
 * Questions admin controller
 */
abstract class Product extends Action
{
    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected $_publicActions = ['edit'];

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Question model factory
     *
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $questionFactory;


    public function __construct(
        Context $context,
        Registry $coreRegistry,
        QuestionFactory $questionFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->questionFactory = $questionFactory;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('NDP_Question::questions_all');

    }
}
