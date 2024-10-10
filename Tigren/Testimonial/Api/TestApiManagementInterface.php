<?php

namespace Tigren\Testimonial\Api;

/**
 *
 */
interface TestApiManagementInterface
{
    /**
     * get test Api data.
     *
     * @param int $id
     *
     * @return \Tigren\Testimonial\Api\Data\TestApiInterface
     * @api
     *
     */
    public function getApiDataTest($id);

    /**
     * @param $data
     * @return mixed
     */

    public function getApiData(int $entity_id);
}