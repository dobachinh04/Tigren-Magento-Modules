<?php

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'rule_id';

    protected function _construct(): void
    {
        $this->_init(
            \Tigren\CustomerGroupCatalog\Model\CustomerGroupCatalog::class,
            \Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog::class
        );
    }
}