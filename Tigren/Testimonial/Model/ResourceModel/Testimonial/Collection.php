<?php

namespace Tigren\Testimonial\Model\ResourceModel\Testimonial;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct(): void
    {
        $this->_init(
            \Tigren\Testimonial\Model\Testimonial::class,
            \Tigren\Testimonial\Model\ResourceModel\Testimonial::class
        );
    }
}