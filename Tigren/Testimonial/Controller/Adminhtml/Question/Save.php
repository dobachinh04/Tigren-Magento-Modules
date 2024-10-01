<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Exception;
use Tigren\Testimonial\Model\TestimonialFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Save
 * @package Tigren\Testimonial\Controller\Adminhtml\Question
 */
class Save extends Action
{
    /**
     * @var TestimonialFactory
     */
    private TestimonialFactory $testimonialFactory;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * Save constructor.
     * @param Context $context
     * @param TestimonialFactory $testimonialFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        TestimonialFactory $testimonialFactory,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->testimonialFactory = $testimonialFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $id = !empty($data['entity_id']) ? $data['entity_id'] : null;

        // Khai báo biến để giữ ảnh cũ
        $oldImage = null;

        // Nếu có ID, tải testimonial cũ để lấy ảnh cũ
        if ($id) {
            $testimonial = $this->testimonialFactory->create()->load($id);
            $oldImage = $testimonial->getProfileImage(); // Lưu lại ảnh cũ
        }

        // Kiểm tra xem có hình ảnh mới được tải lên hay không
        if (!empty($data['profile_image'][0]['name']) && isset($data['profile_image'][0]['tmp_name'])) {
            // Nếu có ảnh mới, giữ lại tên ảnh mới
            $data['profile_image'] = $data['profile_image'][0]['name'];

            // Xóa ảnh cũ nếu có
            if ($oldImage) {
                $this->deleteOldImage($oldImage);
            }
        } else {
            // Nếu không có hình ảnh mới, giữ lại ảnh cũ
            $data['profile_image'] = $oldImage;
        }

        // Chuẩn bị dữ liệu để lưu
        $newData = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'company' => $data['company'] ?? '',
            'rating' => $data['rating'] ?? '',
            'created_at' => $data['created_at'] ?? '',
            'profile_image' => $data['profile_image'] ?? '',
            'status' => isset($data['status']) ? (int)$data['status'] : 0,
            'message' => $data['message'] ?? '',
        ];

        // Tạo hoặc load testimonial
        $testimonial = $this->testimonialFactory->create();
        if ($id) {
            $testimonial->load($id);
        }

        try {
            // Thêm dữ liệu và lưu
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

    /**
     * Xóa ảnh cũ
     *
     * @param string $oldImage
     */
    private function deleteOldImage($oldImage)
    {
        // Đường dẫn đến thư mục chứa ảnh
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $imagePath = $mediaDirectory->getAbsolutePath('tmp/imageUploader/images/') . $oldImage;

        // Kiểm tra xem file có tồn tại không
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa file
        }
    }
}
