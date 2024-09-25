<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\DisableModule\HidePrice\Plugin\Catalog\Product;

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
