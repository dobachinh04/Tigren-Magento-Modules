<?php

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'rule_id';

    protected function _construct(): void
    {
        $this->_init(
            \Tigren\CustomerGroupCatalog\Model\CustomerGroupCatalog::class,
            \Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog::class
        );
    }

//    protected function _initSelect()
//    {
//        parent::_initSelect();
//
//        $this->getSelect()
//            ->joinLeft(
//                ['cg' => $this->getTable('customer_group')],
//                'FIND_IN_SET(cg.customer_group_id, main_table.customer_group_ids)',
//                ['customer_group_name' => 'cg.customer_group_code']
//            )
//            ->joinLeft(
//                ['p' => $this->getTable('catalog_product_entity')],
//                'FIND_IN_SET(p.entity_id, main_table.product_ids)',
//                []
//            )
//            ->joinLeft(
//                ['pv' => $this->getTable('catalog_product_entity_varchar')],
//                'p.entity_id = pv.entity_id AND pv.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE attribute_code = "name" AND entity_type_id = (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = "catalog_product"))',
//                ['product_name' => 'pv.value']
//            )
//            ->joinLeft(
//                ['s' => $this->getTable('store')],
//                'FIND_IN_SET(s.store_id, main_table.store_ids)',
//                ['store_name' => 's.name']
//            );
//
//        return $this;
//    }
}
