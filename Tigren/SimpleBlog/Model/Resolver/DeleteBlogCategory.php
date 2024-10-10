<?php

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class DeleteBlogCategory implements ResolverInterface
{
    private $dataProvider;

    public function __construct(
        \Tigren\SimpleBlog\Model\Resolver\DataProvider\BlogCategory $dataProvider
    ) {
        $this->dataProvider = $dataProvider;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        try {
            // Đảm bảo rằng entity_id được truyền đúng
            if (!isset($args['entity_id'])) {
                throw new GraphQlInputException(__('entity_id is required'));
            }

            return $this->dataProvider->deleteBlogCategory($args['entity_id']);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}
