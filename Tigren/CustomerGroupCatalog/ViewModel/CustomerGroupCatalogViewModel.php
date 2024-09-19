<?php

declare(strict_types=1);

namespace Tigren\CustomerGroupCatalog\ViewModel;

//use Codeception\Util\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

use Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\Collection;
use Tigren\CustomerGroupCatalog\Service\CustomerGroupCatalogProvider;
use Tigren\CustomerGroupCatalog\Model\CustomerGroupCatalog;
use Magento\Framework\App\RequestInterface;
use Magento\Theme\Block\Html\Pager;
use Magento\Framework\View\Element\Template;

class CustomerGroupCatalogViewModel implements ArgumentInterface
{
    public function __construct(
        private CustomerGroupCatalogProvider $customerGroupCatalogProvider,
        private RequestInterface $request,
//        private UrlInterface $url,
//        private StoreManagerInterface $storeManager
    ) {

    }

    public function getCustomerGroupCatalog(int $limit): Collection
    {
        return $this->customerGroupCatalogProvider->getCustomerGroupCatalog($limit, $this->getCurrentPage());
    }

    private function getCurrentPage(): int
    {
        return (int)$this->request->getParam('p', 1);
    }

    public function getPager(Collection $collection, Pager $pagerBlock): string
    {
        $pagerBlock->setUseContainer(false)
            ->setShowPerPage(false)
            ->setShowAmount(false)
            ->setFrameLength(3)
            ->setLimit($collection->getPageSize())
            ->setCollection($collection);

        return $pagerBlock->toHtml();
    }

    public function getCustomerGroupCatalogHtml(Template $block, CustomerGroupCatalog $customerGroupCatalog): string
    {
        $block->setData('customerGroupCatalog', $customerGroupCatalog);
        return $block->toHtml();
    }

    //    public function getProfileImageUrl(CustomerGroupCatalog $customerGroupCatalog): string
    //    {
    //        $filename = $customerGroupCatalog->getData('profile_image');
    //        $profile_image_path = 'tmp/imageUploader/images/';
    //        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    //
    //        return $mediaUrl . $profile_image_path . $filename;
    //    }
}
