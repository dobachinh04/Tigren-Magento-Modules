<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Vendor\Module\Model\Source;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class CustomerOptionsProvider implements OptionSourceInterface
{
    protected $customerCollectionFactory;

    public function __construct(
        CollectionFactory $customerCollectionFactory
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    public function toOptionArray()
    {
        $customerCollection = $this->customerCollectionFactory->create();
        $options = [];

        foreach ($customerCollection as $customer) {
            $options[] = [
                'value' => $customer->getId(),
                'label' => $customer->getFirstname() . ' ' . $customer->getLastname(),
            ];
        }

        return $options;
    }
}
