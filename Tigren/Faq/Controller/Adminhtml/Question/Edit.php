<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\Testimonial\Model\TestimonialFactory;

/**
 *
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var TestimonialFactory
     */
    protected $testimonialFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param TestimonialFactory $testimonialFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        TestimonialFactory $testimonialFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->testimonialFactory = $testimonialFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
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
