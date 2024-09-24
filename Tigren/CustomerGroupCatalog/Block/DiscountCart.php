<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\CustomerGroupCatalog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\Session;

/**
 *
 */
class DiscountCart extends Template
{
    protected $checkoutSession;

    public function __construct(
        Template\Context $context,
        Session $checkoutSession,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    public function getOldPrice($cartItem)
    {
        // Lấy thông tin sản phẩm
        $product = $cartItem->getProduct();

        // Trả về giá cũ (giá gốc của sản phẩm)
        return $product->getPrice();
    }

    public function getFinalPrice($cartItem)
    {
        // Tính toán giá mới dựa trên quy tắc giảm giá
        $discountValue = $this->calculateDiscount($cartItem);

        // Trả về giá sau khi áp dụng giảm giá
        return $cartItem->getPrice() - $discountValue;
    }

    protected function calculateDiscount($cartItem)
    {
        // Lấy thông tin quy tắc giảm giá từ cơ sở dữ liệu (như trong ApplyDiscountInCart.php)
        // Giả sử rằng bạn đã lấy được $data từ quy tắc giảm giá
        $data = $this->getDiscountData($cartItem);

        if ($data) {
            $discountRate = $data['discount_amount'] ?? 0;
            return ($cartItem->getPrice() * $discountRate) / 100;
        }

        return 0;
    }

    protected function getDiscountData($cartItem)
    {
        // Truy vấn cơ sở dữ liệu để lấy thông tin quy tắc giảm giá cho sản phẩm này
        $connection = $this->checkoutSession->getResourceConnection()->getConnection();
        $tableName = $connection->getTableName('tigren_customer_group_catalog_rule');

        $select = $connection->select()
            ->from($tableName)
            ->where('product_ids = ?', $cartItem->getProductId())
            ->where('customer_group_ids = ?', $this->checkoutSession->getQuote()->getCustomerGroupId())
            ->where('start_time <= ?', date('Y-m-d H:i:s'))
            ->where('end_time >= ?', date('Y-m-d H:i:s'))
            ->where('is_active = ?', 1)
            ->order('priority ASC');

        return $connection->fetchRow($select);
    }
}
