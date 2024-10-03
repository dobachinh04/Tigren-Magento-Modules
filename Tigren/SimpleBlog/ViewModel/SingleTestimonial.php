<?php

declare(strict_types=1);

namespace Tigren\Testimonial\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Tigren\Testimonial\Model\Faq;

class SingleTestimonial implements ArgumentInterface
{
    public function __construct(
        private UrlInterface $url,
    ) {
    }

    public function getTestimonialUrl(Faq $testimonial): string
    {
        return $this->url->getBaseUrl() . 'testimonial/' . $testimonial->getData('url_key');
    }
}
