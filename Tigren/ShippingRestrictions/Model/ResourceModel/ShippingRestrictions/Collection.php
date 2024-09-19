<?php

namespace Tigren\ShippingRestrictions\Model\ResourceModel\ShippingRestrictions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'rule_id';

    protected function _construct(): void
    {
        $this->_init(
            \Tigren\ShippingRestrictions\Model\ShippingRestrictions::class,
            \Tigren\ShippingRestrictions\Model\ResourceModel\ShippingRestrictions::class
        );
    }
}