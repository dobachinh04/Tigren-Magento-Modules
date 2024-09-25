<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\DisableModule\HidePrice\Block\Product\View;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;

class NoPrice extends Template
{
    protected $customerSession;

    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function isCustomerLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }
}
