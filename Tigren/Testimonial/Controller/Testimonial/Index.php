<?php

namespace Tigren\Testimonial\Controller\Testimonial;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Tigren\Testimonial\Model\TestimonialFactory;

class Index extends Action
{
    protected $testimonialFactory;

    public function __construct(
        Context $context,
        TestimonialFactory $testimonialFactory
    ) {
        $this->testimonialFactory = $testimonialFactory;
        parent::__construct($context);
    }

    public function execute(): void
    {
        $data = $this->testimonialFactory->create()->getCollection();
        foreach ($data as $value) {
            echo "<pre>";
            print_r($value->getData());
            echo "</pre>";
        }
    }
}