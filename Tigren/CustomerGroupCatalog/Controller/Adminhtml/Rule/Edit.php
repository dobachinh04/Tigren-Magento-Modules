<?php

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\CustomerGroupCatalog\Model\CustomerGroupCatalogFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $customerGroupCatalogFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        CustomerGroupCatalogFactory $customerGroupCatalogFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerGroupCatalogFactory = $customerGroupCatalogFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->customerGroupCatalogFactory->create();

        // Load Customer Group Catalog Rule nếu ID tồn tại
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Customer Group Catalog Rule no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        // Thiết lập tiêu đề trang
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Customer Group Catalog Rule') : __('New Customer Group Catalog Rule')
        );

        return $resultPage;
    }
}
