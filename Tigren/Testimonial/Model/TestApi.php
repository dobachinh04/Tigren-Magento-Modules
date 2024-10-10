<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\Testimonial\Model;

/**
 * Marketplace Product Model.
 *
 * @method \Tigren\Marketplace\Model\ResourceModel\Product _getResource()
 * @method \Tigren\Marketplace\Model\ResourceModel\Product getResource()
 */
class TestApi implements \Tigren\Testimonial\Api\Data\TestApiInterface
{
    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return 10;
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \Tigren\Marketplace\Api\Data\ProductInterface
     */
    public function setId($id)
    {
    }

    /**
     * Get title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return 'this is test title';
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return \Tigren\Marketplace\Api\Data\ProductInterface
     */
    public function setTitle($title)
    {
    }

    /**
     * Get desc.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return 'Hello World';
    }

    /**
     * Set Desc.
     *
     * @param string $desc
     *
     * @return \Tigren\Marketplace\Api\Data\ProductInterface
     */
    public function setDescription($desc)
    {
    }
}