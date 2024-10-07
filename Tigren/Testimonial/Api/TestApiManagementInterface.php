<?php

namespace Tigren\Testimonial\Api;

interface TestApiManagementInterface
{
    /**
     * get test Api data.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Tigren\Testimonial\Api\Data\TestApiInterface
     */
    public function getApiData($id);
}