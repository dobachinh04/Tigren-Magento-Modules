<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\CustomerGroupCatalog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory;

/**
 *
 */
class ApplyDiscountInCart implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $CollectionFactory;

    /**
     * @param LoggerInterface $logger
     * @param CollectionFactory $CollectionFactory
     */
    public function __construct(
        LoggerInterface $logger,
        CollectionFactory $CollectionFactory
    ) {
        $this->logger = $logger;
        $this->CollectionFactory = $CollectionFactory;
    }

    // Kiểm tra tính hợp lệ của rule

    /**
     * @param $rule
     * @param $cartItem
     * @return true
     */
    protected function isRuleValid($rule, $cartItem)
    {
        return true;
    }

    // Tính toán giá sau khi được áp dụng rule

    /**
     * @param $rule
     * @param $basePrice
     * @return float|int
     */
    protected function computeDiscount($rule, $basePrice)
    {
        $discountRate = $rule->getDiscountAmount();

        if ($discountRate <= 0) {
            return 0;
        }

        return ($basePrice * $discountRate) / 100;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        // Lấy thông tin sản phẩm trong giỏ hàng
        $cartItem = $observer->getEvent()->getData('quote_item');
        $cartItem = $cartItem->getParentItem() ? $cartItem->getParentItem() : $cartItem;

        // Lấy collection rule
        $ruleCollection = $this->CollectionFactory->create();
        $ruleCollection->addFieldToFilter('product_ids', ['finset' => $cartItem->getProductId()])
            ->addFieldToFilter('customer_group_ids', ['finset' => $cartItem->getQuote()->getCustomerGroupId()])
            ->addFieldToFilter('start_time', ['lteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('end_time', ['gteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('is_active', 1)
            ->setOrder('priority', 'ASC');

        // Kiểm tra xem có rule giảm giá hay không
        $rule = $ruleCollection->getFirstItem();
        if ($rule->getId() && $this->isRuleValid($rule, $cartItem)) {
            $discountValue = $this->computeDiscount($rule, $cartItem->getPrice());

            if ($discountValue > 0) {
                $finalPrice = $cartItem->getPrice() - $discountValue;

                // Áp dụng giá mới
                $cartItem->setCustomPrice($finalPrice);
                $cartItem->setOriginalCustomPrice($finalPrice);
                $cartItem->getProduct()->setIsSuperMode(true);

                // Ghi log chi tiết
                $this->logger->info(sprintf(
                    'Discount applied: %.2f | Discount Percentage: %.2f%% | New Price: %.2f',
                    $discountValue,
                    $rule->getDiscountAmount(),
                    $finalPrice
                ));
            }
        }
    }
}
