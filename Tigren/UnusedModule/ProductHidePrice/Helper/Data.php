<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\DisableModule\ProductHidePrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    public function getIsEnable()
    {
        return $this->scopeConfig->getValue('tigren_producthideprice/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getWordingHidePrice()
    {
        return $this->scopeConfig->getValue('tigren_producthideprice/general/wording_hide_price',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
