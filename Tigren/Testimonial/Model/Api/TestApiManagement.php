<?php

namespace Tigren\Testimonial\Model\Api;

class TestApiManagement implements \Tigren\Testimonial\Api\TestApiManagementInterface
{
    protected $_testApiFactory;

    public function __construct(
        \Tigren\Testimonial\Model\TestApiFactory $testApiFactory
    ) {
        $this->_testApiFactory = $testApiFactory;
    }

    /**
     * get test Api data.
     *
     * @param int $id
     * @return \Tigren\Testimonial\Api\Data\TestApiInterface
     */
    public function getApiData($id)
    {
        try {
            $model = $this->_testApiFactory->create();
            $model->setId($id);  // Dùng id truyền vào

            if (!$model->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('No data found for the given ID.')
                );
            }

            return $model;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // Handle exception for localized error
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        } catch (\Exception $e) {
            // Handle generic exception
            throw new \Magento\Framework\Exception\LocalizedException(__('Unable to process the request.'));
        }
    }
}
