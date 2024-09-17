<?php

declare(strict_types=1);

namespace Tigren\CustomerGroupCatalog\ViewModel;

//use Codeception\Util\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

use Tigren\Testimonial\Model\ResourceModel\Testimonial\Collection;
use Tigren\Testimonial\Service\TestimonialProvider;
use Tigren\Testimonial\Model\Testimonial;
use Magento\Framework\App\RequestInterface;
use Magento\Theme\Block\Html\Pager;
use Magento\Framework\View\Element\Template;

class TestimonialViewModel implements ArgumentInterface
{
    public function __construct(
        private TestimonialProvider $testimonialProvider,
        private RequestInterface $request,
        private UrlInterface $url,
        private StoreManagerInterface $storeManager
    ) {

    }

    public function getTestimonial(int $limit): Collection
    {
        return $this->testimonialProvider->getTestimonial($limit, $this->getCurrentPage());
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

    public function getTestimonialHtml(Template $block, Testimonial $testimonial):string
    {
        $block->setData('testimonial', $testimonial);
        return $block->toHtml();
    }

    public function getProfileImageUrl(Testimonial $testimonial): string
    {
        $filename = $testimonial->getData('profile_image');
        $profile_image_path = 'tmp/imageUploader/images/';
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl . $profile_image_path . $filename;
    }
}
