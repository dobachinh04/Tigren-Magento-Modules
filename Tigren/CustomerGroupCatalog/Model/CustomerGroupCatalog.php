<?php

namespace Tigren\CustomerGroupCatalog\Model;

use Magento\Framework\Model\AbstractModel;

class CustomerGroupCatalog extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Tigren\Testimonial\Model\ResourceModel\Testimonial::class);
    }
}
