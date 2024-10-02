<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\ShippingRestrictions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory as CartPriceRuleCollectionFactory;

class CartPriceRuleOptionsProvider implements OptionSourceInterface
{
    protected CartPriceRuleCollectionFactory $cartPriceRuleCollectionFactory;

    public function __construct(
        CartPriceRuleCollectionFactory $cartPriceRuleCollectionFactory
    ) {
        $this->cartPriceRuleCollectionFactory = $cartPriceRuleCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $cartPriceRuleCollection = $this->cartPriceRuleCollectionFactory->create()
            ->addFieldToFilter('is_active', 1); // Lọc các quy tắc đang hoạt động

        foreach ($cartPriceRuleCollection as $cartPriceRule) {
            $options[] = [
                'value' => $cartPriceRule->getId(), // Sử dụng ID của cart price rule
                'label' => $cartPriceRule->getName() // Sử dụng tên quy tắc làm label
            ];
        }

        return $options;
    }
}
