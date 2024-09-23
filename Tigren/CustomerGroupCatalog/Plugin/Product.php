<?php

namespace Tigren\CustomerGroupCatalog\Plugin;

class Product
{
    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
//        return $result + 10000;
    }
}