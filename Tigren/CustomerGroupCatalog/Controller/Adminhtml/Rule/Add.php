<?php

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

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
        $resultPage->getConfig()->getTitle()->prepend(__('Add New Customer Group Catalog Rule'));
        return $resultPage;
    }
}