<?php

namespace Tigren\Testimonial\Model\Config;

use Tigren\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class DataProvider extends AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
    protected UrlInterface $urlBuilder;
    private $mediaDirectory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder, // Inject URL Builder
        Filesystem $filesystem,   // Inject Filesystem
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->urlBuilder = $urlBuilder; // Assign URL Builder
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA); // Initialize media directory
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $data = $item->getData();

            // Nếu cột 'profile_image' có giá trị
            if (isset($data['profile_image'])) {
                // Xây dựng đường dẫn đầy đủ của ảnh
                $imageUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . 'tmp/imageUploader/images/' . $data['profile_image'];

                // Đường dẫn thực tế đến file trên server
                $imagePath = $this->mediaDirectory->getAbsolutePath('tmp/imageUploader/images/') . $data['profile_image'];

                // Định dạng lại dữ liệu ảnh cho UI component
                $data['profile_image'] = [
                    [
                        'name' => $data['profile_image'],
                        'url' => $imageUrl,
                        'size' => file_exists($imagePath) ? filesize($imagePath) : 0, // Lấy kích thước file
                        'type' => file_exists($imagePath) ? mime_content_type($imagePath) : 'image/jpeg', // Lấy mime type của file
                    ]
                ];
            }

            $this->_loadedData[$item->getId()] = $data;
        }

        return $this->_loadedData;
    }
}
