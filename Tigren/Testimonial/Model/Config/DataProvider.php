<?php
namespace Tigren\Testimonial\Model\Config;

use Tigren\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;

class DataProvider extends AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
    protected UrlInterface $urlBuilder;
    private $mediaDirectory;
    private $customerFactory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param UrlInterface $urlBuilder
     * @param Filesystem $filesystem
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name, // Thay đổi kiểu thành string
        string $primaryFieldName, // Thay đổi kiểu thành string
        string $requestFieldName, // Thay đổi kiểu thành string
        CollectionFactory $collectionFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        UrlInterface $urlBuilder,
        Filesystem $filesystem,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->customerFactory = $customerCollectionFactory;
        $this->urlBuilder = $urlBuilder;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
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
                        'size' => file_exists($imagePath) ? filesize($imagePath) : 0,
                        'type' => file_exists($imagePath) ? mime_content_type($imagePath) : 'image/jpeg',
                    ]
                ];
            }

            $this->_loadedData[$item->getId()] = $data;
        }

        return $this->_loadedData;
    }
}
