<?php

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Tigren\CustomerGroupCatalog\Model\CustomerGroupCatalogFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    protected $resultRedirectFactory;
    protected $customerGroupCatalogFactory;

    public function __construct(
        Action\Context $context,
        RedirectFactory $resultRedirectFactory,
        CustomerGroupCatalogFactory $customerGroupCatalogFactory
    ) {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->customerGroupCatalogFactory = $customerGroupCatalogFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id'); // Đảm bảo rằng tên tham số là đúng

        if ($id) {
            try {
                $model = $this->customerGroupCatalogFactory->create()->load($id);

                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('The item no longer exists.'));
                    return $resultRedirect->setPath('*/*/index');
                }

                $model->delete();
                $this->messageManager->addSuccessMessage(__('Deleted Successfully.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleting the item: %1',
                    $e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An unexpected error occurred: %1', $e->getMessage()));
            }
        } else {
            $this->messageManager->addErrorMessage(__('No ID Found.'));
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
