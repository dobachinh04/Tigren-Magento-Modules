<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Tigren\Testimonial\Model\TestimonialFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 *
 */
class Delete extends Action
{
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    /**
     * @var TestimonialFactory
     */
    protected $testimonialFactory;
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Action\Context $context
     * @param RedirectFactory $resultRedirectFactory
     * @param TestimonialFactory $testimonialFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Action\Context $context,
        RedirectFactory $resultRedirectFactory,
        TestimonialFactory $testimonialFactory,
        Filesystem $filesystem // Inject Filesystem
    )
    {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->testimonialFactory = $testimonialFactory;
        $this->filesystem = $filesystem; // Initialize Filesystem
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id'); // Đảm bảo rằng tên tham số là đúng

        if ($id) {
            try {
                $model = $this->testimonialFactory->create()->load($id);

                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('The item no longer exists.'));
                    return $resultRedirect->setPath('*/*/index');
                }

                // Lấy tên file ảnh cũ để xóa
                $oldImage = $model->getProfileImage();

                // Xóa testimonial
                $model->delete();

                // Xóa ảnh cũ nếu có
                if ($oldImage) {
                    $this->deleteOldImage($oldImage);
                }

                $this->messageManager->addSuccessMessage(__('Deleted Successfully.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleting the item: %1',
                    $e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An unexpected error occurred: %1', $e->getMessage()));
            }
        } else {
            $this->messageManager->addErrorMessage(__('No ID Found.'));
        }

        return $resultRedirect->setPath('*/*/index');
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

