<?php

namespace Tigren\ShippingRestrictions\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShippingRestrictions extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('tigren_shipping_restrictions_rule', 'rule_id');
    }
}