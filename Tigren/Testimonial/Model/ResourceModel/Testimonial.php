<?php

namespace Tigren\Testimonial\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Testimonial extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('tigren_testimonial', 'entity_id');
    }
}