<?php
namespace Tigren\CustomerGroupCatalog\Model;

use Magento\Framework\Model\AbstractModel;

class RuleHistory extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory::class);
    }
}
