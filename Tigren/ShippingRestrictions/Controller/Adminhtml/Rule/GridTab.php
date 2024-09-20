<?php
declare(strict_types=1);

namespace Tigren\ShippingRestrictions\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class GridTab extends \Magento\Backend\App\Action

{
    /**
     * Page return
     *
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * Execute function
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Page return
     *
     * @return mixed
     */
    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu("ShippingRestrictions_AddCustomTab::popup");
        $resultPage
            ->getConfig()
            ->getTitle()
            ->prepend(__("Tabs Test"));
        return $resultPage;
    }
}