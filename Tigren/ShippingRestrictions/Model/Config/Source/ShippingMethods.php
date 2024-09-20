<?php

namespace Tigren\ShippingRestrictions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Shipping\Model\Config as ShippingConfig;

class ShippingMethods implements OptionSourceInterface
{
    /**
     * @var ShippingConfig
     */
    protected $shippingConfig;

    /**
     * Constructor
     *
     * @param ShippingConfig $shippingConfig
     */
    public function __construct(ShippingConfig $shippingConfig)
    {
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * Return options array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $methods = $this->shippingConfig->getActiveCarriers();
        $options = [];

        foreach ($methods as $carrierCode => $carrierModel) {
            if ($carrierModel->getAllowedMethods()) {
                $carrierTitle = $carrierModel->getConfigData('title');
                foreach ($carrierModel->getAllowedMethods() as $methodCode => $methodTitle) {
                    $options[] = [
                        'value' => $carrierCode . '_' . $methodCode,
                        'label' => $carrierTitle . ' - ' . $methodTitle,
                    ];
                }
            }
        }

        return $options;
    }
}
