<?php

namespace Tigren\HidePrice\Plugin\Catalog\Product;

use Magento\Customer\Model\Session;

class ProductList
{
    protected $customerSession;

    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function beforeToHtml(\Magento\Catalog\Block\Product\ProductList\Item\Block $subject)
    {
        if (!$this->customerSession->isLoggedIn()) {
            $subject->setTemplate('Tigren_HidePrice::product/list/no_price.phtml');
        }
    }
}
