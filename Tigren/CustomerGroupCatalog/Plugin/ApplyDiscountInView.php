<?php

namespace Tigren\CustomerGroupCatalog\Plugin;

use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory;

class ApplyDiscountInView
{
    protected LoggerInterface $logger;
    protected CustomerSession $customerSession;
    protected CollectionFactory $ruleCollectionFactory;

    public function __construct(
        LoggerInterface $logger,
        CustomerSession $customerSession,
        CollectionFactory $ruleCollectionFactory
    ) {
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        // Log thông tin để xác nhận plugin đã được kích hoạt
        $this->logger->info('Plugin Tigren_CustomerGroupCatalog::afterGetPrice activated.');

        // Lấy thông tin nhóm khách hàng và ID sản phẩm
        $customerGroupId = $this->getCustomerGroupId();
        $productId = $subject->getId();

        // Log thông tin sản phẩm và nhóm khách hàng
        $this->logger->info(sprintf('Processing product ID: %d for customer group ID: %d', $productId,
            $customerGroupId));

        // Lấy collection của Rule
        $ruleCollection = $this->ruleCollectionFactory->create();
        $ruleCollection->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('start_time', ['lteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('end_time', ['gteq' => date('Y-m-d H:i:s')])
            ->setOrder('priority', 'ASC');

        // Filter theo danh sách product_ids và customer_group_ids
        $ruleCollection->getSelect()->where(
            'FIND_IN_SET(?, product_ids)', $productId
        )->where(
            'FIND_IN_SET(?, customer_group_ids)', $customerGroupId
        );

        // Kiểm tra nếu có rule giảm giá
        $rule = $ruleCollection->getFirstItem();
        if ($rule->getId()) {
            $this->logger->info('Discount rule found.');

            // Tính toán giá sau khi giảm
            $discountAmount = $rule->getDiscountAmount();
            $discountValue = ($result * $discountAmount) / 100;
            $finalPrice = $result - $discountValue;

            // Log giá sau khi giảm
            $this->logger->info(sprintf(
                'Applying discount: original price: %.2f, discount: %.2f, final price: %.2f',
                $result, $discountValue, $finalPrice
            ));

            return $finalPrice;
        }

        // Trả về giá gốc nếu không có rule giảm giá
        $this->logger->info('No discount rule found. Returning original price: ' . $result);
        return $result;
    }

    protected function getCustomerGroupId()
    {
        // Lấy thông tin nhóm khách hàng từ session
        $customerGroupId = $this->customerSession->getCustomer()->getGroupId();
        $this->logger->info('Customer group ID: ' . $customerGroupId);

        return $customerGroupId;
    }
}


