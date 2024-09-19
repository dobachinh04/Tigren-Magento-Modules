<?php

namespace Tigren\CustomerGroupCatalog\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Block\Adminhtml\Category\Tab\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\BlockInterface;

class AssignProducts extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'products/assign_products.phtml';

    /**
     * @var Product
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
     * @var CollectionFactory
     */
    protected $productFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param EncoderInterface $jsonEncoder
     * @param CollectionFactory $productFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EncoderInterface $jsonEncoder,
        CollectionFactory $productFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->productFactory = $productFactory;
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
                'Tigren\CustomerGroupCatalog\Block\Adminhtml\Tab\Productgrid',
                'category.product.grid'
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
    public function getProductsJson()
    {
        $entity_id = $this->getRequest()->getParam('entity_id');
        $productFactory = $this->productFactory->create();
        $productFactory->addFieldToSelect(['product_id', 'position']);
        $productFactory->addFieldToFilter('entity_id', ['eq' => $entity_id]);
        $result = [];
        if (!empty($productFactory->getData())) {
            foreach ($productFactory->getData() as $rhProducts) {
                $result[$rhProducts['product_id']] = '';
            }
            return $this->jsonEncoder->encode($result);
        }
        return '{}';
    }

    public function getItem()
    {
        return $this->registry->registry('my_item');
    }
}