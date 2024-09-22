<?php

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Exception;
use Tigren\CustomerGroupCatalog\Model\CustomerGroupCatalogFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Save
 * @package Tigren\Testimonial\Controller\Adminhtml\Question
 */
class Save extends Action
{
    /**
     * @var CustomerGroupCatalogFactory
     */
    private $customerGroupCatalogFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param CustomerGroupCatalogFactory $customerGroupCatalogFactory
     */
    public function __construct(
        Context $context,
        CustomerGroupCatalogFactory $customerGroupCatalogFactory
    ) {
        parent::__construct($context);
        $this->customerGroupCatalogFactory = $customerGroupCatalogFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $id = !empty($data['rule_id']) ? $data['rule_id'] : null;

        $newData = [
            'name' => $data['name'] ?? '',

            // Giả sử đây là mảng, chuyển thành chuỗi phân cách bằng dấu phẩy
            //            'customer_group_ids' => isset($data['customer_group_ids']) ? implode(',', $data['customer_group_ids']) : '',
            //            'store_ids' => isset($data['store_ids']) ? implode(',', $data['store_ids']) : '',
            //            'product_ids' => isset($data['product_ids']) ? implode(',', $data['product_ids']) : '',

            'discount_amount' => $data['discount_amount'] ?? '',
            'start_time' => $data['start_time'] ?? '',
            'end_time' => $data['end_time'] ?? '',
            'priority' => $data['priority'] ?? '',
            'product_ids' => $data['product_ids'] ?? '',
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 0,
        ];

        // Tạo hoặc load customerGroupCatalog
        $customerGroupCatalog = $this->customerGroupCatalogFactory->create();
        if ($id) {
            $customerGroupCatalog->load($id);
        }

        try {
            // Add dữ liệu và save
            $customerGroupCatalog->addData($newData);
            $customerGroupCatalog->save();

            // Thông báo thành công
            $this->messageManager->addSuccessMessage(__('You saved the Customer Group Catalog Rule.'));
        } catch (Exception $e) {
            // Thông báo lỗi nếu có
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        // Redirect về trang index
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
