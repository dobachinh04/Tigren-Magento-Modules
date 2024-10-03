<?php

namespace Tigren\Faq\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Faq extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('tigren_faq', 'entity_id');
    }
}