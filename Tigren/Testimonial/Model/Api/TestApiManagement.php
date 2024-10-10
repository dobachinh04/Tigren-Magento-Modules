<?php

namespace Tigren\Testimonial\Model\Api;

use Tigren\Testimonial\Api\Data\TestimonialInterface;
use Tigren\Testimonial\Model\TestimonialFactory;
use Tigren\Testimonial\Model\ResourceModel\Testimonial as TestimonialResource;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class TestApiManagement implements \Tigren\Testimonial\Api\TestApiManagementInterface
{
    const SEVERE_ERROR = 0;
    const SUCCESS = 1;
    const LOCAL_ERROR = 2;

    protected $_testApiFactory;

    protected $resource;
    protected $factory;

    public function __construct(
        \Tigren\Testimonial\Model\TestApiFactory $testApiFactory,
        TestimonialResource $resource,
        TestimonialFactory $factory
    ) {
        $this->_testApiFactory = $testApiFactory;
        $this->resource = $resource;
        $this->factory = $factory;
    }

    /**
     * get test Api data.
     *
     * @param int $id
     *
     * @return \Tigren\Testimonial\Api\Data\TestApiInterface
     * @api
     *
     */
    public function getApiDataTest($id)
    {
        try {
            $model = $this->_testApiFactory
                ->create();

            if (!$model->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('no data found')
                );
            }

            return $model;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $returnArray['error'] = $e->getMessage();
            $returnArray['status'] = 0;
            $this->getJsonResponse(
                $returnArray
            );
        } catch (\Exception $e) {
            $this->createLog($e);
            $returnArray['error'] = __('unable to process request');
            $returnArray['status'] = 2;
            $this->getJsonResponse(
                $returnArray
            );
        }
    }

    public function getApiData(int $entity_id)
    {
        $model = $this->factory->create();
        $this->resource->load($model, $entity_id);
        $model->setEntityId($entity_id);
        $model = $this->_testApiFactory->create();
        $this->resource->load($model, $id); // Tải model từ database dựa vào $id

        if (!$model->getId()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('No data found')
            );
        }

        return $model;
    }

}