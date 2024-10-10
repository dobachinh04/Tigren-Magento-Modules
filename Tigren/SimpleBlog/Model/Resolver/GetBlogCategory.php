<?php

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tigren\SimpleBlog\Model\CategoryFactory;

class GetBlogCategory implements ResolverInterface
{
    protected $categoryFactory;

    public function __construct(CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
    }

    public function resolve(
        \Magento\Framework\GraphQl\Config\Element\Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $categoryId = $args['entity_id'];
        $category = $this->categoryFactory->create()->load($categoryId);

        if (!$category->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('Blog category with ID "%1" does not exist.', $categoryId)
            );
        }

        return [
            'entity_id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'status' => $category->getStatus(),
            'created_at' => $category->getCreatedAt(),
            'updated_at' => $category->getUpdatedAt(),
        ];
    }
}
