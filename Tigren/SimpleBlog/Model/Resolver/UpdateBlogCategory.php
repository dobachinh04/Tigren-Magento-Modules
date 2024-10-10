<?php

namespace Tigren\SimpleBlog\Model\Resolver;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class UpdateBlogCategory implements ResolverInterface
{
    private $dataProvider;

    public function __construct(
        \Tigren\SimpleBlog\Model\Resolver\DataProvider\BlogCategory $dataProvider
    )
    {
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
            return $this->dataProvider->updateBlogCategory($args['entity_id'], $args['input']);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}
