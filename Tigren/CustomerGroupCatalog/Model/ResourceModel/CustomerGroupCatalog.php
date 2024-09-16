<?php

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerGroupCatalog extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('tigren_customer_group_catalog_rule', 'rule_id');
    }
}