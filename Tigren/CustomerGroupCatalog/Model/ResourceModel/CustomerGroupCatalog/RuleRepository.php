<?php

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog;

class RuleRepository
{
    protected $collectionFactory;

    public function __construct(\Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getActiveRules()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('start_time', ['lteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('end_time', ['gteq' => date('Y-m-d H:i:s')]);
        return $collection;
    }
}
