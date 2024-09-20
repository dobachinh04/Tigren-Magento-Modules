<?php

declare(strict_types=1);

namespace Tigren\CustomerGroupCatalog\Service;

use Magento\Framework\DB\Select;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory\Collection;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory\CollectionFactory;

class RuleHistoryProvider
{
    public function __construct(
        private CollectionFactory $collectionFactory
    ) {

    }

    public function getRuleHistory(int $limit, int $currentPage): Collection
    {
        $collection = $this->getCollection($limit);
        $collection->setOrder('entity_id', Select::SQL_DESC);
        $collection->setPageSize($limit);
        $collection->setCurPage($currentPage);

        return $collection;
    }

    private function getCollection(int $limit): Collection
    {
        return $this->collectionFactory->create();
    }
}