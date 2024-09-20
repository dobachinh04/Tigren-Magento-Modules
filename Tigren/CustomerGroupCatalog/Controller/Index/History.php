<?php

declare(strict_types=1);

namespace Tigren\CustomerGroupCatalog\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class History implements HttpGetActionInterface
{
    public function __construct(
        private PageFactory $pageFactory
    ) {

    }

    public function execute(): ResultInterface
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set(__('Tigren Discount Usage History'));

        return $page;
    }
}