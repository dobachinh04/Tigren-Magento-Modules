<?php

declare(strict_types=1);

namespace Tigren\CustomerGroupCatalog\Service;

use Magento\Framework\DB\Select;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\Collection;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory;

class CustomerGroupCatalogProvider
{
    public function __construct(
        private CollectionFactory $collectionFactory
    ) {

    }

    public function getCustomerGroupCatalog(int $limit, int $currentPage): Collection
    {
        $collection = $this->getCollection($limit);
        $collection->setOrder('end_time', Select::SQL_DESC);
        $collection->setPageSize($limit);
        $collection->setCurPage($currentPage);

        return $collection;
    }

    private function getCollection(int $limit): Collection
    {
        return $this->collectionFactory->create();
    }
}