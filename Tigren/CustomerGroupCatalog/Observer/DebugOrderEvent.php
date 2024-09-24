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

class DebugOrderEvent implements ObserverInterface
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $this->logger->info("Event 'sales_order_place_after' triggered.");

        // Bạn có thể thêm thông tin chi tiết về đơn hàng tại đây nếu cần
        $order = $observer->getEvent()->getOrder(); // Lấy đối tượng đơn hàng
        $this->logger->info("Order ID: " . $order->getId()); // Ghi lại ID đơn hàng
    }
}

