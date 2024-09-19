<?php

namespace Tigren\HidePriceAndCart\Model\Config\Source\Customer;

use Magento\Framework\Option\ArrayInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

class Group implements ArrayInterface
{
    protected $_options;
    protected $_collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->_collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        if (null === $this->_options) {
            $groups = $this->_collectionFactory->create();
            $this->_options = $groups->toOptionArray();
        }
        return $this->_options;
    }
}
