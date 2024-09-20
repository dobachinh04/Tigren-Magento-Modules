<?php
namespace Tigren\CustomerGroupCatalog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class RuleHistory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('tigren_customer_group_catalog_rule_history', 'entity_id');
    }
}
