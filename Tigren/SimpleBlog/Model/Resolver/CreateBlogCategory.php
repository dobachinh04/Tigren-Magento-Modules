<?php

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\App\RequestInterface;
use Tigren\SimpleBlog\Model\CategoryFactory;

class CreateBlogCategory implements ResolverInterface
{
    protected $categoryFactory;

    public function __construct(
        CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // Kiểm tra input
        if (!isset($args['input']['name']) || !isset($args['input']['description'])) {
            throw new GraphQlInputException(__('Name and Description are required'));
        }

        // Tạo mới category
        $category = $this->categoryFactory->create();
        $category->setData([
            'name' => $args['input']['name'],
            'description' => $args['input']['description'],
            'status' => $args['input']['status'] ?? 1
        ]);
        $category->save();

        return [
            'entity_id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'status' => $category->getStatus()
        ];
    }
}
