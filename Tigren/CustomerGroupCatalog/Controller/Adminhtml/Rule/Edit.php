<?php

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\Testimonial\Model\TestimonialFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $testimonialFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        TestimonialFactory $testimonialFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->testimonialFactory = $testimonialFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->testimonialFactory->create();

        // Load testimonial nếu ID tồn tại
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This testimonial no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        // Thiết lập tiêu đề trang
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Testimonial') : __('New Testimonial')
        );

        return $resultPage;
    }
}
