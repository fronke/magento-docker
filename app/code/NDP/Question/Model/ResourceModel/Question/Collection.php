<?php

namespace NDP\Question\Model\ResourceModel\Question;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Question table
     *
     * @var string
     */
    protected $_questionTable = null;


    /**
     * Question status table
     *
     * @var string
     */
    protected $_questionStatusTable = null;

    /**
     * Question data
     *
     * @var \NDP\Question\Helper\Data
     */
    protected $_questionData = null;


    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \NDP\Question\Helper\Data $questionData
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param mixed $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \NDP\Question\Helper\Data $questionData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_questionData = $questionData;
        $this->_storeManager = $storeManager;

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Define module
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('NDP\Question\Model\Question', 'NDP\Question\Model\ResourceModel\Question');
    }

     /**
     * Add customer filter
     *
     * @param int|string $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFilter('customer', $this->getConnection()->quoteInto('main_table.customer_id=?', $customerId), 'string');
        return $this;
    }

    /**
     * Add store filter
     *
     * @param int|int[] $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        $this->addFilter('customer', $this->getConnection()->quoteInto('main_table.store_id=?', $storeId), 'string');
        return $this;
    }


    /**
     * Add entity filter
     *
     * @param int|string $entity
     * @param int $productId
     * @return $this
     */
    public function addEntityFilter($productId)
    {
        $this->addFilter(
            'product_id',
            $this->getConnection()->quoteInto('main_table.product_id=?', $productId),
            'string'
        );

        return $this;
    }

    /**
     * Add status filter
     *
     * @param int|string $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        if (is_string($status)) {
            $statuses = array_flip($this->_questionData->getQuestionStatuses());
            $status = isset($statuses[$status]) ? $statuses[$status] : 0;
        }
        if (is_numeric($status)) {
            $this->addFilter('status', $this->getConnection()->quoteInto('main_table.status_id=?', $status), 'string');
        }
        return $this;
    }

    /**
     * Set date order
     *
     * @param string $dir
     * @return $this
     */
    public function setDateOrder($dir = 'DESC')
    {
        $this->setOrder('main_table.priority', $dir);
        $this->setOrder('main_table.created_at', $dir);
        return $this;
    }




    /**
     * Load data
     *
     * @param boolean $printQuery
     * @param boolean $logQuery
     * @return $this
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->_eventManager->dispatch('question_question_collection_load_before', ['collection' => $this]);
        parent::load($printQuery, $logQuery);

        return $this;
    }


    /**
     * Get question table
     *
     * @return string
     */
    protected function getQuestionTable()
    {
        if ($this->_questionTable === null) {
            $this->_questionTable = $this->getTable('question');
        }
        return $this->_questionTable;
    }



    /**
     * Get question status table
     *
     * @return string
     */
    protected function getQuestionStatusTable()
    {
        if ($this->_questionStatusTable === null) {
            $this->_questionStatusTable = $this->getTable('question_status');
        }
        return $this->_questionStatusTable;
    }
}
