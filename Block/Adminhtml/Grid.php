<?php

// @codingStandardsIgnoreFile

namespace NDP\Question\Block\Adminhtml;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Question action pager
     *
     * @var \NDP\Question\Helper\Action\Pager
     */
    protected $_questionActionPager = null;

    /**
     * Question data
     *
     * @var \NDP\Question\Helper\Data
     */
    protected $_questionData = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Question collection model factory
     *
     * @var \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory
     */
    protected $_productsFactory;

    /**
     * Question model factory
     *
     * @var \NDP\Question\Model\QuestionFactory
     */
    protected $_questionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \NDP\Question\Model\QuestionFactory $questionFactory
     * @param \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory $productsFactory
     * @param \NDP\Question\Helper\Data $questionData
     * @param \NDP\Question\Helper\Action\Pager $questionActionPager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \NDP\Question\Model\QuestionFactory $questionFactory,
        \NDP\Question\Model\ResourceModel\Question\Product\CollectionFactory $productsFactory,
        \NDP\Question\Helper\Data $questionData,
        \NDP\Question\Helper\Action\Pager $questionActionPager,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_productsFactory = $productsFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_questionData = $questionData;
        $this->_questionActionPager = $questionActionPager;
        $this->_questionFactory = $questionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Initialize grid
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('questionGrid');
        $this->setDefaultSort('created_at');
    }






    /**
     * Save search results
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _afterLoadCollection()
    {
        /** @var $actionPager \NDP\Question\Helper\Action\Pager */
        $actionPager = $this->_questionActionPager;
        $actionPager->setStorageId('questions');
        $actionPager->setItems($this->getCollection()->getResultingIds());

        return parent::_afterLoadCollection();
    }

    /**
     * Prepare collection
     *
     * @return \NDP\Question\Block\Adminhtml\Grid
     */
    protected function _prepareCollection()
    {
        /** @var $model \NDP\Question\Model\Question */
        $model = $this->_questionFactory->create();
        /** @var $collection \NDP\Question\Model\ResourceModel\Question\Product\Collection */
        $collection = $this->_productsFactory->create();

        $collection->getSelect()->columns(['question_replied' => new \Zend_Db_Expr('if(LENGTH(answer_content)>0,1,0)')]);

        if ($this->getProductId() || $this->getRequest()->getParam('productId', false)) {
            $productId = $this->getProductId();
            if (!$productId) {
                $productId = $this->getRequest()->getParam('productId');
            }
            $this->setProductId($productId);
            $collection->addEntityFilter($this->getProductId());
        }

        if ($this->getCustomerId() || $this->getRequest()->getParam('customerId', false)) {
            $customerId = $this->getCustomerId();
            if (!$customerId) {
                $customerId = $this->getRequest()->getParam('customerId');
            }
            $this->setCustomerId($customerId);
            $collection->addCustomerFilter($this->getCustomerId());
        }

        if ($this->getRequest()->getParam('question_replied', 0)) {
            $replied = $this->getRequest()->getParam('question_replied');

           $collection->addRepliedFilter($replied);
        }

        if ($this->_coreRegistry->registry('usePendingFilter') === true) {
            $collection->addStatusFilter($model->getPendingStatus());
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return \Magento\Backend\Block\Widget\Grid
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'question_id',
            [
                'header' => __('ID'),
                'filter_index' => 'rt.question_id',
                'index' => 'question_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created'),
                'type' => 'datetime',
                'filter_index' => 'rt.created_at',
                'index' => 'question_created_at',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        if (!$this->_coreRegistry->registry('usePendingFilter')) {
            $this->addColumn(
                'status',
                [
                    'header' => __('Status'),
                    'type' => 'options',
                    'options' => $this->_questionData->getQuestionStatuses(),
                    'filter_index' => 'rt.status_id',
                    'index' => 'status_id'
                ]
            );
        }

//        $this->addColumn(
//            'question_replied',
//            [
//                'index' => 'question_replied',
//                'header' => __('Replied'),
//                'type' => 'options',
//                'filter' => false,
//                'filter_index' => 'question_replied',
//                'options' => array(
//                    1 => __('Yes'),
//                    0 => __('No')
//                )
//            ]
//        );

        $this->addColumn(
            'question_content',
            [
                'header' => __('Content'),
                'filter_index' => 'rt.question_content',
                'index' => 'question_content',
                'type' => 'text',
                'truncate' => 50,
                'escape' => true
            ]
        );

        $this->addColumn(
            'answer_content',
            [
                'header' => __('Answer'),
                'filter_index' => 'rt.answer_content',
                'index' => 'answer_content',
                'type' => 'text',
                'truncate' => 20,
                'escape' => false
            ]
        );

        $this->addColumn(
            'priority',
            [
                'header' => __('Priority'),
                'filter_index' => 'rt.priority',
                'index' => 'priority',
                'type' => 'text'
            ]
        );




        $this->addColumn(
            'name',
            ['header' => __('Product'), 'type' => 'text', 'index' => 'name', 'escape' => true]
        );

        $this->addColumn(
            'sku',
            [
                'header' => __('SKU'),
                'type' => 'text',
                'index' => 'sku',
                'escape' => true
            ]
        );

        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getQuestionId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'question/product/edit',
                            'params' => [
                                'productId' => $this->getProductId(),
                                'customerId' => $this->getCustomerId(),
                                'ret' => $this->_coreRegistry->registry('usePendingFilter') ? 'pending' : null,
                            ],
                        ],
                        'field' => 'id',
                    ],
                ],
                'filter' => false,
                'sortable' => false
            ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid mass actions
     *
     * @return void
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->setMassactionIdFilter('rt.question_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('questions');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl(
                    '*/*/massDelete',
                    ['ret' => $this->_coreRegistry->registry('usePendingFilter') ? 'pending' : 'index']
                ),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_questionData->getQuestionStatusesOptionArray();
        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'update_status',
            [
                'label' => __('Update Status'),
                'url' => $this->getUrl(
                    '*/*/massUpdateStatus',
                    ['ret' => $this->_coreRegistry->registry('usePendingFilter') ? 'pending' : 'index']
                ),
                'additional' => [
                    'status' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses,
                    ],
                ]
            ]
        );
    }

    /**
     * Get row url
     *
     * @param \NDP\Question\Model\Question|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'question/product/edit',
            [
                'id' => $row->getQuestionId(),
                'productId' => $this->getProductId(),
                'customerId' => $this->getCustomerId(),
                'ret' => $this->_coreRegistry->registry('usePendingFilter') ? 'pending' : null
            ]
        );
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        if ($this->getProductId() || $this->getCustomerId()) {
            return $this->getUrl(
                'question/product' . ($this->_coreRegistry->registry('usePendingFilter') ? 'pending' : ''),
                ['productId' => $this->getProductId(), 'customerId' => $this->getCustomerId()]
            );
        } else {
            return $this->getCurrentUrl();
        }
    }



}
