<?php

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\Exception\CouldNotSaveException;
use Tigren\SimpleBlog\Model\CategoryFactory;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

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
        if (!isset($args['name']) || !isset($args['description']) || !isset($args['status'])) {
            throw new GraphQlInputException(__('Missing required parameters.'));
        }

        $category = $this->categoryFactory->create();
        $category->setData('name', $args['name']);
        $category->setData('description', $args['description']);
        $category->setData('status', $args['status']);

        try {
            $category->save();
            return $category->getData();
        } catch (CouldNotSaveException $e) {
            throw new GraphQlInputException(__('Could not save the blog category.'));
        }
    }
}
