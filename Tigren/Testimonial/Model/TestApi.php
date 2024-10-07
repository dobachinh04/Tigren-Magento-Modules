<?php

namespace Tigren\Testimonial\Model;

class TestApi implements \Tigren\Testimonial\Api\Data\TestApiInterface
{
    protected $_id;
    protected $_title;
    protected $_description;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDescription($desc)
    {
        $this->_description = $desc;
        return $this;
    }
}
