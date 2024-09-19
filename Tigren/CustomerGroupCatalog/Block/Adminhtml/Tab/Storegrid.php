<?php

namespace Tigren\CustomerGroupCatalog\Block\Adminhtml\Tab;

use Magento\Store\Model\ResourceModel\Store\CollectionFactory as StoreCollectionFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Grid\Extended;

class Storegrid extends Extended
{
    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var StoreCollectionFactory
     */
    protected $storeCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param StoreCollectionFactory $storeCollectionFactory
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        StoreCollectionFactory $storeCollectionFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Initialize the grid
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('store_grid');
        $this->setDefaultSort('store_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare the collection
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->storeCollectionFactory->create();
        $collection->addFieldToSelect(['store_id', 'name']);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'store_id',
            [
                'header' => __('Store ID'),
                'index' => 'store_id',
                'type' => 'number',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Store Name'),
                'index' => 'name',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Get grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/rule/storegrids', ['_current' => true]);
    }
}
