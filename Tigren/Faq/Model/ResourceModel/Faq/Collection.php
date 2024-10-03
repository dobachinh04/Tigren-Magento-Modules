<?php

namespace Tigren\Faq\Model\ResourceModel\Faq;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct(): void
    {
        $this->_init(
            \Tigren\Faq\Model\Faq::class,
            \Tigren\Faq\Model\ResourceModel\Faq::class
        );
    }
}