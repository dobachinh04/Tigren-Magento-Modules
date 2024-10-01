<?php declare(strict_types=1);
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\Testimonial\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;

/**
 *
 */
class ImageTempUpload extends Action implements HttpPostActionInterface
{
    /**
     * @var WriteInterface
     */
    private WriteInterface $mediaDirectory;

    /**
     * @param Context $context
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        private UploaderFactory $uploaderFactory,
        private StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $fileUploader = $this->uploaderFactory->create(['fileId' => 'profile_image']);
            $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'webp']);
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);
            $fileUploader->setFilesDispersion(false);

            $profile_image_path = 'tmp/imageUploader/images/';
            $result = $fileUploader->save($this->mediaDirectory->getAbsolutePath($profile_image_path));

            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $filename = ltrim(str_replace('\\', '/', $result['file']), '/');

            $result['url'] = $mediaUrl . $profile_image_path . $filename;

            return $jsonResult->setData($result);
        } catch (LocalizedException $e) {
            return $jsonResult->setData(['errorcode' => 0, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return $jsonResult->setData([
                'errorcode' => 0,
                'error' => __('There is a problem while uploading your image.')
            ]);
        }
    }
}
