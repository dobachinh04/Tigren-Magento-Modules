<?php

namespace Tigren\CustomerGroupCatalog\Block\Adminhtml\Tab;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as CustomerGroupCollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;
use Magento\Store\Model\Store;
use Magento\Backend\Block\Widget\Grid\Extended;

class CustomerGroupgrid extends Extended
{
    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var CustomerGroupCollectionFactory
     */
    protected $customerGroupCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param CustomerGroupCollectionFactory $customerGroupCollectionFactory
     * @param Registry $coreRegistry
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        CustomerGroupCollectionFactory $customerGroupCollectionFactory,
        Registry $coreRegistry,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->customerGroupCollectionFactory = $customerGroupCollectionFactory;
        $this->coreRegistry = $coreRegistry;
        $this->moduleManager = $moduleManager;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customer_group_grid');
        $this->setDefaultSort('customer_group_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('customer_group_id')) {
            $this->setDefaultFilter(['in_customer_groups' => 1]);
        } else {
            $this->setDefaultFilter(['in_customer_groups' => 0]);
        }
        $this->setSaveParametersInSession(true);
    }

    /**
     * [get store id]
     *
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = $this->customerGroupCollectionFactory->create();
        $collection->addFieldToSelect(['customer_group_id', 'customer_group_code']);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_customer_groups') {
            $groupIds = $this->_getSelectedCustomerGroups();
            if (empty($groupIds)) {
                $groupIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('customer_group_id', ['in' => $groupIds]);
            } else {
                if ($groupIds) {
                    $this->getCollection()->addFieldToFilter('customer_group_id', ['nin' => $groupIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_customer_groups',
            [
                'type' => 'checkbox',
                'html_name' => 'customer_group_ids',
                'required' => true,
                'values' => $this->_getSelectedCustomerGroups(),
                'align' => 'center',
                'index' => 'customer_group_id',
            ]
        );

        $this->addColumn(
            'customer_group_id',
            [
                'header' => __('ID'),
                'width' => '50px',
                'index' => 'customer_group_id',
                'type' => 'number',
            ]
        );
        $this->addColumn(
            'customer_group_code',
            [
                'header' => __('Customer Group'),
                'index' => 'customer_group_code',
                'header_css_class' => 'col-type',
                'column_css_class' => 'col-type',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/rule/grids', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedCustomerGroups()
    {
        $groupIds = array_keys($this->getSelectedCustomerGroups());
        return $groupIds;
    }

    /**
     * @return array
     */
    public function getSelectedCustomerGroups()
    {
        $id = $this->getRequest()->getParam('customer_group_id');
        $model = $this->customerGroupCollectionFactory->create()->addFieldToFilter('customer_group_id', $id);
        $groups = [];
        foreach ($model as $key => $value) {
            $groups[] = $value->getCustomerGroupId();
        }
        $groupId = [];
        foreach ($groups as $obj) {
            $groupId[$obj] = ['position' => "0"];
        }
        return $groupId;
    }
}
