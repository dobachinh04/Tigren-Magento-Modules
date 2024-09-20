<?php

namespace Tigren\ShippingRestrictions\Controller\Adminhtml\Rule;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;

/**
 * Class Add
 * @package Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule
 */
class Add extends Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Configuration Shipping Restrictions Rules'));
        return $resultPage;
    }
}