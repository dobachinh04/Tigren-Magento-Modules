<?php

namespace Tigren\CustomerGroupCatalog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory as RuleHistoryResource;
use Tigren\CustomerGroupCatalog\Model\RuleHistoryFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory\CollectionFactory as RuleCollectionFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory;
use Psr\Log\LoggerInterface;

class SaveRuleHistory implements ObserverInterface
{
    protected RuleHistoryFactory $ruleHistoryFactory;
    protected RuleHistoryResource $ruleHistoryResource;
    protected RuleCollectionFactory $ruleCollectionFactory;
    protected LoggerInterface $logger;
    protected CollectionFactory $CollectionFactory;

    public function __construct(
        RuleHistoryFactory $ruleHistoryFactory,
        RuleHistoryResource $ruleHistoryResource,
        RuleCollectionFactory $ruleCollectionFactory,
        LoggerInterface $logger,
        CollectionFactory $CollectionFactory,
    ) {
        $this->ruleHistoryFactory = $ruleHistoryFactory;
        $this->ruleHistoryResource = $ruleHistoryResource;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->logger = $logger;
        $this->CollectionFactory = $CollectionFactory;
    }

    public function execute(Observer $observer)
    {
        $this->logger->info("Observer execution started (sales_order_save_after)");

        // Lấy đối tượng order từ sự kiện
        $order = $observer->getEvent()->getOrder();
        if (!$order || !$order->getEntityId()) {
            $this->logger->error("Order object is null or Order ID is invalid.");
            return;
        }

        $customerId = $order->getCustomerId(); // Lấy Customer ID
        $orderId = $order->getEntityId(); // Lấy Order ID

        // Kiểm tra xem Order ID có hợp lệ không
        if (!$orderId) {
            $this->logger->error("Order ID is empty or invalid.");
            return;
        }

        $this->logger->info("Order ID: " . $orderId . ", Customer ID: " . $customerId);

        // Logic để lấy rule_id dựa vào order/customer/group
        $ruleId = $this->getRuleIdByOrder($order);

        if ($ruleId) {
            $this->logger->info("Rule ID found: " . $ruleId);

            // Tạo một lịch sử mới cho rule
            $history = $this->ruleHistoryFactory->create();
            $history->setOrderId($orderId);
            $history->setCustomerId($customerId);
            $history->setRuleId($ruleId);
            $history->setCreatedAt(date('Y-m-d H:i:s'));

            // Lưu lịch sử
            try {
                $this->ruleHistoryResource->save($history);
                $this->logger->info("Rule history saved for order ID: " . $orderId);
            } catch (\Exception $e) {
                $this->logger->error("Failed to save rule history: " . $e->getMessage());
            }
        } else {
            $this->logger->info("No rule ID found for order ID: " . $orderId);
        }
    }

    /**
     * Logic để lấy rule_id dựa trên order hoặc customer
     */
    private function getRuleIdByOrder($order)
    {
        // Lấy tất cả các sản phẩm trong đơn hàng
        $itemCollection = $order->getAllItems();
        $productIds = [];
        foreach ($itemCollection as $item) {
            $productIds[] = $item->getProductId();
        }

        // Lấy Customer Group ID từ Order
        $customerGroupId = $order->getCustomerGroupId();

        $this->logger->info("Input Product IDs: " . implode(',', $productIds));
        $this->logger->info("Input Customer Group ID: " . $customerGroupId);

        $ruleCollection = $this->CollectionFactory->create();
        $ruleCollection->addFieldToFilter('product_ids', ['finset' => implode(',', $productIds)])
            ->addFieldToFilter('customer_group_ids', ['finset' => $customerGroupId])
            ->addFieldToFilter('start_time', ['lteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('end_time', ['gteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('is_active', 1)
            ->setOrder('priority', 'ASC');

        // Ghi lại số lượng quy tắc tìm thấy
        $this->logger->info("Rule Collection Count: " . $ruleCollection->getSize());

        if ($ruleCollection->getSize() > 0) {
            // Lấy rule ID đầu tiên tìm thấy
            $rule = $ruleCollection->getFirstItem();
            $ruleId = $rule->getId();
            $this->logger->info("Found Rule ID: " . $ruleId);

            // Giả sử bạn có đối tượng đơn hàng là $order
            $order->setData('applied_rule_id', $ruleId); // Lưu rule ID vào trường dữ liệu của đơn hàng
            $order->save(); // Lưu đơn hàng

            return $ruleId; // Trả về rule ID đã tìm thấy
        }

        return null; // Không tìm thấy rule ID
    }


}
