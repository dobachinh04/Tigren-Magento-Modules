<?php

namespace Tigren\ShippingRestrictions\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;

class DiscardSubsequent implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Yes')],
            ['value' => 0, 'label' => __('No')]
        ];
    }
}
