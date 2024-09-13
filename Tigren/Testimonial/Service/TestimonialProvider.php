<?php

declare(strict_types=1);

namespace Tigren\Testimonial\Service;

use Magento\Framework\DB\Select;
use Tigren\Testimonial\Model\ResourceModel\Testimonial\Collection;
use Tigren\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;

class TestimonialProvider
{
    public function __construct(
        private CollectionFactory $collectionFactory
    ) {

    }

    public function getTestimonial(int $limit, int $currentPage): Collection
    {
        $collection = $this->getCollection($limit);
        $collection->setOrder('created_at', Select::SQL_DESC);
        $collection->setPageSize($limit);
        $collection->setCurPage($currentPage);

        return $collection;
    }

    private function getCollection(int $limit): Collection
    {
        return $this->collectionFactory->create();
    }
}