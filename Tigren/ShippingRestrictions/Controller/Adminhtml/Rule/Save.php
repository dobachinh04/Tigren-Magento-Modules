<?php

namespace Tigren\ShippingRestrictions\Controller\Adminhtml\Rule;

use Tigren\ShippingRestrictions\Model\ShippingRestrictionsFactory;
use Tigren\ShippingRestrictions\Model\ResourceModel\ShippingRestrictions\CollectionFactory;
use Magento\Backend\App\Action;

class Save extends Action
{
    private $ruleFactory;
    private $collectionFactory;

    public function __construct(
        Action\Context $context,
        ShippingRestrictionsFactory $ruleFactory,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        //        var_dump($data);
        //        exit;
        if (!$data) {
            $this->messageManager->addErrorMessage(__('No data found to save.'));
            return $resultRedirect->setPath('*/*/');
        }

        $id = !empty($data['rule_id']) ? $data['rule_id'] : null;

        if (isset($data['name'])) {
            $name = $data['name'];
        } else {
            throw new \Exception('Name không tồn tại.');
        }
        $newData = [
            'name' => $name,
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'priority' => $data['priority'],
            'discount_amount' => $data['discount_amount'],
            'status' => $data['status'],
            'customer_group_ids' => $data['customer_group_ids'],
            'store_ids' => $data['store_ids'],
            'description' => $data['description'],
            'discard_subsequent' => $data['discard_subsequent']
        ];


        $rule = $this->ruleFactory->create();
        $collection = $this->collectionFactory->create();
        $listRule = $collection->getData();

        if (isset($id)) {
            $rule->load($id);
        }

        try {
            $customerGroupIds = $data['customer_group_ids'] ?? [];
            $storeIds = $data['store_ids'] ?? [];

            if (count($customerGroupIds) <= 1 && count($storeIds) <= 1) {
                $newData['customer_group_ids'] = $customerGroupIds[0] ?? '';
                $newData['store_ids'] = implode(',', $storeIds);
                $rule->addData($newData);
                $rule->save();
                $this->messageManager->addSuccessMessage(__('The rule has been successfully saved.'));
            } else {
                $savedRules = 0;
                foreach ($customerGroupIds as $groupId) {
                    foreach ($storeIds as $storeId) {
                        $exists = false;
                        foreach ($listRule as $existingRule) {
                            if ($existingRule['customer_group_ids'] == $groupId
                                && $existingRule['store_ids'] == $storeId) {
                                $exists = true;
                                break;
                            }
                        }
                        if (!$exists) {
                            $newData['customer_group_ids'] = $groupId;
                            $newData['store_ids'] = $storeId;
                            $newRule = $this->ruleFactory->create();
                            $newRule->setId(null);
                            $newRule->addData($newData);
                            $newRule->save();
                            $savedRules++;
                        }
                    }
                }

                if ($savedRules > 0) {
                    $this->messageManager->addSuccessMessage(__('%1 rules have been saved.', $savedRules));
                } else {
                    $this->messageManager->addNoticeMessage(__('No new rules were saved as they already exist.'));
                }
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the rule: %1',
                $exception->getMessage()));
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
