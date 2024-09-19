<?php

namespace Tigren\HidePriceAndCart\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Tigren\HidePriceAndCart\Helper\Data as ProductAvailableHelper;
use Magento\Framework\Exception\LocalizedException;

class QuoteObserver implements ObserverInterface
{
    protected $_helper;

    public function __construct(
        ProductAvailableHelper $helper
    ) {
        $this->_helper = $helper;
    }

    public function execute(Observer $observer)
    {
        if (!$this->_helper->isAvailableAddToCart()) {
            throw new LocalizedException(
                __('You cannot add products to cart.')
            );
        }
    }
}
