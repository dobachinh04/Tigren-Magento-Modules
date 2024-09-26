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
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
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
        if (!isset($args['input']['name']) || !isset($args['input']['description']) || !isset($args['input']['status'])) {
            throw new GraphQlInputException(__('Required parameters are missing'));
        }

        $categoryData = [
            'name' => $args['input']['name'],
            'description' => $args['input']['description'],
            'status' => $args['input']['status'],
        ];

        $category = $this->categoryFactory->create();
        $category->setData($categoryData);
        $category->save();

        return [
            'category_id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'status' => $category->getStatus(),
        ];
    }
}
