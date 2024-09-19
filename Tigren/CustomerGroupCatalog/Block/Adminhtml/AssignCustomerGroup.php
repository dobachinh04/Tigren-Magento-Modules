<?php

namespace Tigren\CustomerGroupCatalog\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as CustomerGroupCollectionFactory;
use Magento\Framework\View\Element\BlockInterface;

class AssignCustomerGroup extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'customers/assign_customer_group.phtml';

    /**
     * @var BlockInterface
     */
    protected $blockGrid;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var CustomerGroupCollectionFactory
     */
    protected $customerGroupFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param EncoderInterface $jsonEncoder
     * @param CustomerGroupCollectionFactory $customerGroupFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EncoderInterface $jsonEncoder,
        CustomerGroupCollectionFactory $customerGroupFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->customerGroupFactory = $customerGroupFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Tigren\CustomerGroupCatalog\Block\Adminhtml\Tab\CustomerGroupgrid',
                'customer.group.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getCustomerGroupsJson()
    {
        $entity_id = $this->getRequest()->getParam('entity_id');
        $customerGroupFactory = $this->customerGroupFactory->create();
        $customerGroupFactory->addFieldToSelect(['customer_group_id', 'customer_group_code']);
        $result = [];
        foreach ($customerGroupFactory->getData() as $group) {
            $result[$group['customer_group_id']] = $group['customer_group_code'];
        }
        return $this->jsonEncoder->encode($result);
    }

    public function getItem()
    {
        return $this->registry->registry('my_item');
    }
}
