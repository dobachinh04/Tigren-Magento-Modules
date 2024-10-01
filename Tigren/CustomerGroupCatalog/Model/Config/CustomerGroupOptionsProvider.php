<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as CustomerGroupCollectionFactory;

class CustomerGroupOptionsProvider implements OptionSourceInterface
{
    protected $customerGroupCollectionFactory;

    public function __construct(
        CustomerGroupCollectionFactory $customerGroupCollectionFactory
    ) {
        $this->customerGroupCollectionFactory = $customerGroupCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $customerGroupCollection = $this->customerGroupCollectionFactory->create();

        foreach ($customerGroupCollection as $customerGroup) {
            $options[] = [
                'value' => $customerGroup->getId(), // Sử dụng ID của customer group
                'label' => $customerGroup->getCustomerGroupCode() // Sử dụng customer group code làm label
            ];
        }

        return $options;
    }
}
