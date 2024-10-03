<?php

namespace Tigren\Testimonial\Model\Customer;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;

class Options implements OptionSourceInterface
{
    protected $customerCollectionFactory;

    public function __construct(
        CustomerCollectionFactory $customerCollectionFactory)
    {
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $customerCollection = $this->customerCollectionFactory->create();

        foreach ($customerCollection as $customer) {
            $options[] = [
                'value' => $customer->getId(),
                'label' => $customer->getName() // Có thể sử dụng getEmail() hoặc thuộc tính khác
            ];
        }

        return $options;
    }
}
