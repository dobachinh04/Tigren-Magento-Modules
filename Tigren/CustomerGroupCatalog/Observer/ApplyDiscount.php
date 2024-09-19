<?php

namespace Tigren\CustomerGroupCatalog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory;
use Psr\Log\LoggerInterface;

class ApplyDiscount implements ObserverInterface
{
    protected $customerSession;
    protected $cartRepository;
    protected $ruleCollectionFactory;
    protected $logger;

    public function __construct(
        CustomerSession $customerSession,
        CartRepositoryInterface $cartRepository,
        CollectionFactory $ruleCollectionFactory,
        LoggerInterface $logger
    ) {
        $this->customerSession = $customerSession;
        $this->cartRepository = $cartRepository;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $this->logger->info('hehe observer triggered.');
        $quote = $observer->getEvent()->getQuote();
        $customer = $this->customerSession->getCustomer();
        $rules = $this->ruleCollectionFactory->create()->getActiveRulesForCustomer($customer);

        foreach ($quote->getAllItems() as $item) {
            foreach ($rules as $rule) {
                if (in_array($item->getProductId(), explode(',', $rule->getProductIds()))) {
                    $discountPercent = $rule->getDiscountAmount();
                    $item->setDiscountAmount($item->getPrice() * ($discountPercent / 100));
                    $item->setBaseDiscountAmount($item->getBasePrice() * ($discountPercent / 100));
                }
            }
        }
    }
}
