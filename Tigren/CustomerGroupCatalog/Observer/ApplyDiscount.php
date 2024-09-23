<?php

namespace Tigren\CustomerGroupCatalog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;

class ApplyDiscount implements ObserverInterface
{
    protected LoggerInterface $logger;
    protected ResourceConnection $resourceConnection;

    public function __construct(
        LoggerInterface $logger,
        ResourceConnection $resourceConnection
    ) {
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
    }

    // Kiểm tra tính hợp lệ của rule
    protected function isRuleValid($data, $cartItem)
    {
        return true;
    }

    // Tính toán giá sau khi được áp dụng rule
    protected function computeDiscount($data, $basePrice)
    {
        $discountRate = $data['discount_amount'] ?? 0;

        if ($discountRate <= 0) {
            return 0;
        }

        return ($basePrice * $discountRate) / 100;
    }

    public function execute(Observer $observer)
    {
        // Lấy thông tin sản phẩm trong giỏ hàng
        $cartItem = $observer->getEvent()->getData('quote_item');
        $cartItem = $cartItem->getParentItem() ? $cartItem->getParentItem() : $cartItem;

        // Kết nối đến database
        $dbConnection = $this->resourceConnection->getConnection();
        $tableName = $dbConnection->getTableName('tigren_customer_group_catalog_rule');

        // Tạo truy vấn tìm rule giảm giá
        $query = $dbConnection->select()
            ->from($tableName)
            ->where('product_ids = ?', $cartItem->getProductId())
            ->where('customer_group_ids = ?', $cartItem->getQuote()->getCustomerGroupId())
            ->where('start_time <= ?', date('Y-m-d H:i:s'))
            ->where('end_time >= ?', date('Y-m-d H:i:s'))
            ->where('is_active = ?', 1)
            ->order('priority ASC');

        // Kiểm tra kết nối
        try {
            $data = $dbConnection->fetchRow($query);
        } catch (\Exception $e) {
            $this->logger->error('Error applying discount: ' . $e->getMessage());
            return;
        }

        // Kiểm tra xem có rule giảm giá hay không
        if ($data && $this->isRuleValid($data, $cartItem)) {
            $discountValue = $this->computeDiscount($data, $cartItem->getPrice());

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
                    $data['discount_amount'],
                    $finalPrice
                ));
            }
        }
    }
}
