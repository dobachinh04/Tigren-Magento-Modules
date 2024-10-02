<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\ShippingRestrictions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Shipping\Model\Config as ShippingConfig;

class ShippingMethodOptionsProvider implements OptionSourceInterface
{
    protected ShippingConfig $shippingConfig;

    public function __construct(
        ShippingConfig $shippingConfig
    ) {
        $this->shippingConfig = $shippingConfig;
    }

    public function toOptionArray()
    {
        $options = [];
        $shippingMethods = $this->shippingConfig->getAllCarriers(); // Lấy tất cả các carrier

        foreach ($shippingMethods as $carrierCode => $carrier) {
            // Nếu carrier hỗ trợ các phương thức, thêm chúng vào danh sách options
            if ($carrier->isActive()) {
                foreach ($carrier->getAllowedMethods() as $methodCode => $methodTitle) {
                    $options[] = [
                        'value' => $carrierCode . '_' . $methodCode, // Giá trị bao gồm cả carrier và method code
                        'label' => $methodTitle // Tiêu đề của phương thức vận chuyển
                    ];
                }
            }
        }

        return $options;
    }
}
