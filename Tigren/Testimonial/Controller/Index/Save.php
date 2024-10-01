<?php

namespace Tigren\Testimonial\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Tigren\Testimonial\Model\TestimonialFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Customer\Model\Session as CustomerSession;

class Save extends Action
{
    protected $testimonialFactory;
    protected $fileUploaderFactory;
    protected $mediaDirectory;
    protected $messageManager;
    protected $customerSession;

    public function __construct(
        Context                                     $context,
        TestimonialFactory                          $testimonialFactory,
        \Magento\Framework\File\UploaderFactory     $fileUploaderFactory,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CustomerSession                             $customerSession
    )
    {
        $this->testimonialFactory = $testimonialFactory;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->mediaDirectory = $directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPostValue();
            $testimonial = $this->testimonialFactory->create();
            $testimonial->setName($data['name'])
                ->setEmail($data['email'])
                ->setRating($data['rating'])
                ->setMessage($data['message'])
                ->setCompany($data['company']);

            // Lấy thông tin khách hàng từ session nếu đăng nhập
            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
                $testimonial->setCustomerId($customer->getId());
            }

            // Xử lý upload hình ảnh
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
                try {
                    $uploader = $this->fileUploaderFactory->create(['fileId' => 'profile_image']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $result = $uploader->save($this->mediaDirectory . '/tmp/imageUploader/images');
                    $testimonial->setProfileImage($result['file']);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('Error uploading image.'));
                    return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
                }
            }

            // Lưu testimonial
            try {
                $testimonial->save();
                $this->messageManager->addSuccessMessage(__('Testimonial has been successfully created.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Unable to save testimonial.'));
            }
        }
        // Điều hướng về trang testimonial
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('testimonial');
    }
}
