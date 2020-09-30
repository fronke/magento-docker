<?php

namespace NDP\Question\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

class Question extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Question table
     *
     * @var string
     */
    protected $_questionTable;

    /**
     * Question status table
     *
     * @var string
     */
    protected $_questionStatusTable;

    /**
     * Question store table
     *
     * @var string
     */
    protected $_questionStoreTable;


    /**
     * Core date model
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;


    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        $this->_date = $date;
        $this->_storeManager = $storeManager;

        parent::__construct($context, $connectionName);
    }

    /**
     * Define main table. Define other tables name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('question', 'question_id');
        $this->_questionTable = $this->getTable('question');
        $this->_questionStatusTable = $this->getTable('question_status');
    }


    /**
     * Retrieves total questions
     *
     * @param bool $approvedOnly
     * @param int $storeId
     * @return int
     */
    public function getTotalQuestions($productId, $approvedOnly = false, $storeId = 0)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->_questionTable,
            ['question_count' => new \Zend_Db_Expr('COUNT(*)')]
        )->where(
            "{$this->_questionTable}.product_id = :product_id"
        );
         $bind = [':product_id' => $productId];

        if ($approvedOnly) {
            $select->where("{$this->_questionTable}.status_id = :status_id");
            $bind[':status_id'] = \NDP\Question\Model\Question::STATUS_PUBLIC;
        }
        return $connection->fetchOne($select, $bind);
    }


    /**
     * Delete questions by product id.
     * Better to call this method in transaction, because operation performed on two separated tables
     *
     * @param int $productId
     * @return $this
     */
    public function deleteQuestionsByProductId($productId)
    {
        $this->getConnection()->delete(
            $this->_questionTable,
            [
                'product_id=?' => $productId
            ]
        );
        return $this;
    }
}
