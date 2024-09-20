<?php

namespace Tigren\ShippingRestrictions\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\ShippingRestrictions\Model\ShippingRestrictionsFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $shippingRestrictionsFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        ShippingRestrictionsFactory $shippingRestrictionsFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->shippingRestrictionsFactory = $shippingRestrictionsFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->shippingRestrictionsFactory->create();

        // Load Customer Group Catalog Rule nếu ID tồn tại
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Shipping Restrictions Rule no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        // Thiết lập tiêu đề trang
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Shipping Restrictions Rule') : __('New Shipping Restrictions Rule')
        );

        return $resultPage;
    }
}
