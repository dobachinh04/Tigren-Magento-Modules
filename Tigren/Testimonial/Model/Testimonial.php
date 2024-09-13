<?php

namespace Tigren\Testimonial\Model;

use Magento\Framework\Model\AbstractModel;

class Testimonial extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Tigren\Testimonial\Model\ResourceModel\Testimonial::class);
    }
}
