<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;

class GetBlogCategory implements ResolverInterface
{
    protected $categoryCollectionFactory;

    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $categoryId = $args['entity_id'] ?? null;

        if (!$categoryId) {
            throw new GraphQlInputException(__('Entity ID is required.'));
        }

        $collection = $this->categoryCollectionFactory->create();
        $category = $collection->getItemById($categoryId);

        if (!$category) {
            throw new GraphQlNoSuchEntityException(__('Blog category not found.'));
        }

        return $category->getData();
    }
}
