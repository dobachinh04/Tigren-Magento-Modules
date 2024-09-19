<?php

namespace Tigren\ShippingRestrictions\Model;

use Magento\Framework\Model\AbstractModel;

class ShippingRestrictions extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Tigren\ShippingRestrictions\Model\ResourceModel\ShippingRestrictions::class);
    }
}
