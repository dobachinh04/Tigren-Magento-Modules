<?php

namespace Tigren\HidePrice\Plugin\Catalog\Product;

use Magento\Customer\Model\Session;
use Psr\Log\LoggerInterface;

class View
{
    protected $customerSession;
    protected $logger;

    public function __construct(Session $customerSession, LoggerInterface $logger)
    {
        $this->customerSession = $customerSession;
        $this->logger = $logger;
    }

    public function beforeToHtml(\Magento\Catalog\Block\Product\View $subject)
    {
        //        $this->logger->info('HidePrice Plugin executed');
        //        $this->logger->info('Customer logged in: ' . ($this->customerSession->isLoggedIn() ? 'Yes' : 'No'));

        if (!$this->customerSession->isLoggedIn()) {
            $subject->setTemplate('Tigren_HidePrice::product/view/no_price.phtml');
        } else {
            // Bạn có thể thêm log để kiểm tra nếu có bất kỳ vấn đề nào xảy ra tại đây
            $this->logger->info('Lỗi rồi!');
        }
    }

}
