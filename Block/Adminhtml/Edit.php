<?php

namespace NDP\Question\Block\Adminhtml;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Question action pager
     *
     * @var \NDP\Question\Helper\Action\Pager
     */
    protected $_questionActionPager = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Question model factory
     *
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $_questionFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     * @param \NDP\Question\Helper\Action\Pager $questionActionPager
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \NDP\Question\Model\QuestionFactory $questionFactory,
        \NDP\Question\Helper\Action\Pager $questionActionPager,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_questionActionPager = $questionActionPager;
        $this->_questionFactory = $questionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Initialize edit question
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'NDP_Question';
        $this->_controller = 'adminhtml';

        /** @var $actionPager \NDP\Question\Helper\Action\Pager */
        $actionPager = $this->_questionActionPager;
        $actionPager->setStorageId('questions');

        $questionId = $this->getRequest()->getParam('id');
        $prevId = $actionPager->getPreviousItemId($questionId);
        $nextId = $actionPager->getNextItemId($questionId);
        if ($prevId !== false) {
            $this->addButton(
                'previous',
                [
                    'label' => __('Previous'),
                    'onclick' => 'setLocation(\'' . $this->getUrl('question/*/*', ['id' => $prevId]) . '\')'
                ],
                3,
                10
            );

            $this->addButton(
                'save_and_previous',
                [
                    'label' => __('Save and Previous'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'save',
                                'target' => '#edit_form',
                                'eventData' => ['action' => ['args' => ['next_item' => $prevId]]],
                            ],
                        ],
                    ]
                ],
                3,
                11
            );
        }
        if ($nextId !== false) {
            $this->addButton(
                'save_and_next',
                [
                    'label' => __('Save and Next'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'save',
                                'target' => '#edit_form',
                                'eventData' => ['action' => ['args' => ['next_item' => $nextId]]],
                            ],
                        ],
                    ]
                ],
                3,
                100
            );

            $this->addButton(
                'next',
                [
                    'label' => __('Next'),
                    'onclick' => 'setLocation(\'' . $this->getUrl('question/*/*', ['id' => $nextId]) . '\')'
                ],
                3,
                105
            );
        }
        $this->buttonList->update('save', 'label', __('Save Question'));
        $this->buttonList->update('save', 'id', 'save_button');
        $this->buttonList->update('delete', 'label', __('Delete Question'));

        if ($this->getRequest()->getParam('productId', false)) {
            $this->buttonList->update(
                'back',
                'onclick',
                'setLocation(\'' . $this->getUrl(
                    'catalog/product/edit',
                    ['id' => $this->getRequest()->getParam('productId', false)]
                ) . '\')'
            );
        }

        if ($this->getRequest()->getParam('customerId', false)) {
            $this->buttonList->update(
                'back',
                'onclick',
                'setLocation(\'' . $this->getUrl(
                    'customer/index/edit',
                    ['id' => $this->getRequest()->getParam('customerId', false)]
                ) . '\')'
            );
        }

        if ($this->getRequest()->getParam('ret', false) == 'pending') {
            $this->buttonList->update('back', 'onclick', 'setLocation(\'' . $this->getUrl('catalog/*/pending') . '\')');
            $this->buttonList->update(
                'delete',
                'onclick',
                'deleteConfirm(' . '\'' . __(
                    'Are you sure you want to do this?'
                ) . '\' ' . '\'' . $this->getUrl(
                    '*/*/delete',
                    [$this->_objectId => $this->getRequest()->getParam($this->_objectId), 'ret' => 'pending']
                ) . '\'' . ')'
            );
            $this->_coreRegistry->register('ret', 'pending');
        }

        if ($this->getRequest()->getParam($this->_objectId)) {
            $questionData = $this->_questionFactory->create()->load($this->getRequest()->getParam($this->_objectId));
            $this->_coreRegistry->register('question_data', $questionData);
        }
    }

    /**
     * Get edit question header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $questionData = $this->_coreRegistry->registry('question_data');
        if ($questionData && $questionData->getId()) {
            return __("Edit Question '%1'", $this->escapeHtml($questionData->getQuestionContent()));
        } else {
            return __('New Question');
        }
    }
}
