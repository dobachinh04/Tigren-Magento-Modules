<?php
namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tigren\CustomerGroupCatalog\Model\RuleHistory as RuleHistoryModel;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory as RuleHistoryResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(RuleHistoryModel::class, RuleHistoryResourceModel::class);
    }
}
