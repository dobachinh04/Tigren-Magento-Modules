<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class ProductOptionsProvider implements OptionSourceInterface
{
    protected $productCollectionFactory;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('name')
            ->addAttributeToFilter('status', 1);

        foreach ($productCollection as $product) {
            $options[] = [
                'value' => $product->getId(), // Sử dụng ID của sản phẩm
                'label' => $product->getName() // Sử dụng tên của sản phẩm làm label
            ];
        }

        return $options;
    }
}
