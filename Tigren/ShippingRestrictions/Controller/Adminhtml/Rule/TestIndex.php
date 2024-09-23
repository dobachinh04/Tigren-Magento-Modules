<?php
declare(strict_types=1);

namespace Tigren\ShippingRestrictions\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Add extends Action
{
    /**
     * Page Factory
     *
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Execute function
     *
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu("ShippingRestrictions_AddCustomTab::popup");
        $resultPage
            ->getConfig()
            ->getTitle()
            ->prepend(__("Shipping Restrictions Rules Configuration"));
        return $resultPage;
    }
}
