<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog;

/**
 * RuleRepository
 */
class RuleRepository
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(\Tigren\CustomerGroupCatalog\Model\ResourceModel\CustomerGroupCatalog\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return mixed
     */
    public function getActiveRules()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('start_time', ['lteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('end_time', ['gteq' => date('Y-m-d H:i:s')]);
        return $collection;
    }
}
