<?php

namespace Tigren\Faq\Model;

use Magento\Framework\Model\AbstractModel;

class Faq extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Tigren\Faq\Model\ResourceModel\Faq::class);
    }
}
