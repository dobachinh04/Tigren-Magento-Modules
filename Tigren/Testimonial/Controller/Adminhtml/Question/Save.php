<?php

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Exception;
use Tigren\Testimonial\Model\TestimonialFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Save
 * @package Tigren\Testimonial\Controller\Adminhtml\Question
 */
class Save extends Action
{
    /**
     * @var TestimonialFactory
     */
    private $testimonialFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param TestimonialFactory $testimonialFactory
     */
    public function __construct(
        Context $context,
        TestimonialFactory $testimonialFactory
    ) {
        parent::__construct($context);
        $this->testimonialFactory = $testimonialFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        //                var_dump($data);
        //                dd();
        if (!empty($data['profile_image'][0]['name']) && isset($data['profile_image'][0]['tmp_name'])) {
            $data['profile_image'] = $data['profile_image'][0]['name'];
        } else {
            unset($data['profile_image']);
        }
        //        $data['update_time'] = null;

        $id = !empty($data['entity_id']) ? $data['entity_id'] : null;
        $image = !empty($data['profile_image']) ? $data['profile_image'] : null;

        // Chuẩn bị dữ liệu
        $newData = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'company' => $data['company'] ?? '',
            'rating' => $data['rating'] ?? '',
            'created_at' => $data['created_at'] ?? '',
            'profile_image' => $image ?? '',
            'status' => isset($data['status']) ? (int)$data['status'] : 0,
            'message' => $data['message'] ?? '',
        ];

        // Tạo hoặc load testimonial
        $testimonial = $this->testimonialFactory->create();
        if ($id) {
            $testimonial->load($id);
        }

        try {
            // Add dữ liệu và save
            $testimonial->addData($newData);
            $testimonial->save();

            // Thông báo thành công
            $this->messageManager->addSuccessMessage(__('You saved the Testimonial.'));
        } catch (Exception $e) {
            // Thông báo lỗi nếu có
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        // Redirect về trang index
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
