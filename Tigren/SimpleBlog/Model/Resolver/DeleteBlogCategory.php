<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\Exception\CouldNotDeleteException;
use Tigren\SimpleBlog\Model\CategoryFactory;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class DeleteBlogCategory implements ResolverInterface
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
        $entityId = $args['entity_id'] ?? null;

        if (!$entityId) {
            throw new GraphQlInputException(__('Entity ID is required.'));
        }

        $category = $this->categoryFactory->create()->load($entityId);
        if (!$category->getId()) {
            throw new GraphQlInputException(__('Blog category not found.'));
        }

        try {
            $category->delete();
            return true;
        } catch (CouldNotDeleteException $e) {
            throw new GraphQlInputException(__('Could not delete the blog category.'));
        }
    }
}
