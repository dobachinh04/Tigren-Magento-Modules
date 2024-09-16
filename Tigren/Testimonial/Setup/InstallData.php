<?php

namespace Tigren\Testimonial\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        // Thêm attribute vào form adminhtml
        $eavSetup->addAttributeToGroup(
            \Magento\Customer\Model\Customer::ENTITY,
            'General', // Thay đổi group nếu cần
            null,
            'is_created_testimonial',
            999 // Vị trí trong form
        );

        // Đảm bảo attribute xuất hiện trong form
        $attribute = $eavSetup->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'is_created_testimonial');
        $attribute->setData('used_in_forms', ['adminhtml_customer']);
        $eavSetup->saveAttribute(\Magento\Customer\Model\Customer::ENTITY, $attribute);
    }
}
