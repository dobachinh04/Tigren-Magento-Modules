<?php

namespace Tigren\Testimonial\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Tigren\Testimonial\Model\TestimonialFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Request\Http as HttpRequest;

class Save extends Action
{
    /**
     * @var TestimonialFactory
     */
    protected $testimonialFactory;
    /**
     * @var \Magento\Framework\File\UploaderFactory
     */
    protected $fileUploaderFactory;
    /**
     * @var string
     */
    protected $mediaDirectory;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;


    /**
     * @param Context $context
     * @param TestimonialFactory $testimonialFactory
     * @param \Magento\Framework\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context                                     $context,
        TestimonialFactory                          $testimonialFactory,
        \Magento\Framework\File\UploaderFactory     $fileUploaderFactory,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->testimonialFactory = $testimonialFactory;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->mediaDirectory = $directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
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
            // Handle file upload
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
            // Save testimonial
            try {
                $testimonial->save();
                $this->messageManager->addSuccessMessage(__('Testimonial has been successfully created.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Unable to save testimonial.'));
            }
        }
        // Redirect or render view
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('testimonial');
    }
}