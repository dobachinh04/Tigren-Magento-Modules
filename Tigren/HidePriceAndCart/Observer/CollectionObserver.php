<?php

namespace Tigren\HidePriceAndCart\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Tigren\HidePriceAndCart\Helper\Data as ProductAvailableHelper;

class CollectionObserver implements ObserverInterface
{
    protected $_helper;

    public function __construct(
        ProductAvailableHelper $helper
    ) {
        $this->_helper = $helper;
    }

    public function execute(Observer $observer)
    {
        if (!$this->_helper->isAvailablePrice()) {
            $collection = $observer->getEvent()->getCollection();
            foreach ($collection as $product) {
                $product->setCanShowPrice(false);
            }
        }
    }
}
