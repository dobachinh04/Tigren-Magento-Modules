<?php

namespace Tigren\CustomerGroupCatalog\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\ResourceModel\Store\CollectionFactory as StoreCollectionFactory;
use Magento\Framework\View\Element\BlockInterface;

class AssignStore extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'stores/assign_store.phtml';

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
     * @var StoreCollectionFactory
     */
    protected $storeFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param EncoderInterface $jsonEncoder
     * @param StoreCollectionFactory $storeFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EncoderInterface $jsonEncoder,
        StoreCollectionFactory $storeFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->storeFactory = $storeFactory;
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
                'Tigren\CustomerGroupCatalog\Block\Adminhtml\Tab\Storegrid',
                'store.grid'
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
    public function getStoresJson()
    {
        $storeCollection = $this->storeFactory->create();
        $storeCollection->addFieldToSelect(['store_id', 'name']);
        $result = [];
        foreach ($storeCollection->getData() as $store) {
            $result[$store['store_id']] = $store['name'];
        }
        return $this->jsonEncoder->encode($result);
    }

    public function getItem()
    {
        return $this->registry->registry('my_item');
    }
}
