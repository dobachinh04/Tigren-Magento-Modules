<?php

namespace Tigren\CustomerGroupCatalog\Model;

use Magento\Framework\Model\AbstractModel;

class CustomerGroupCatalog extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog::class);
    }

    //    /**
    //     * Prepare data before saving
    //     *
    //     * @return $this
    //     */
    //    protected function _beforeSave()
    //    {
    //        $productIds = $this->getData('product_ids');
    //        if (is_array($productIds)) {
    //            $this->setData('product_ids', json_encode($productIds));
    //        }
    //        return parent::_beforeSave();
    //    }
    //
    //    /**
    //     * Prepare data after loading
    //     *
    //     * @return $this
    //     */
    //    protected function _afterLoad()
    //    {
    //        $productIds = $this->getData('product_ids');
    //        if ($productIds) {
    //            $this->setData('product_ids', json_decode($productIds, true));
    //        }
    //        return parent::_afterLoad();
    //    }
}
