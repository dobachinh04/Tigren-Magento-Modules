<?php

namespace Tigren\ShippingRestrictions\Model\Config;

use Tigren\ShippingRestrictions\Model\ShippingRestrictionsFactory;
use Tigren\ShippingRestrictions\Model\ResourceModel\ShippingRestrictions\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $_loadedData = [];
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (!empty($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $data = $item->getData();

            // Lấy dữ liệu từ bảng join, đảm bảo field tồn tại trong collection
            $data['customer_group_name'] = $item->getData('customer_group_name') ?: ''; // Mặc định nếu không có dữ liệu
            $data['product_name'] = $item->getData('product_name') ?: '';
            $data['store_name'] = $item->getData('store_name') ?: '';

            $this->_loadedData[$item->getId()] = $data;
        }

        return $this->_loadedData;
    }
}
