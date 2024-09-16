<?php

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;

/**
 * Class Add
 * @package Tigren\Testimonial\Controller\Adminhtml\Testimonial
 */
class Add extends Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Add New Testimonial'));
        return $resultPage;
    }
}