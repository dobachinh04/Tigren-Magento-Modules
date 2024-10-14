<?php

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;

// Import đúng namespace
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

// Import đúng namespace
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;

class GetBlogCategories implements ResolverInterface
{
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function resolve(
        Field $field, // Thay đổi kiểu tham số
        $context,
        ResolveInfo $info, // Thay đổi kiểu tham số
        ?array $value = null, // Thêm tham số value
        ?array $args = null // Thêm tham số args
    )
    {
        $collection = $this->collectionFactory->create();
        $items = [];

        foreach ($collection as $item) {
            $items[] = [
                'entity_id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'status' => $item->getStatus(),
                'created_at' => $item->getCreatedAt(),
                'updated_at' => $item->getUpdatedAt(),
            ];
        }

        return ['items' => $items];
    }
}
