<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

namespace Tigren\SimpleBlog\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class BlogCategory
{
    protected $_blogCategoryFactory;

    protected $_objectManager;

    public function __construct(
        \Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory $blogCategoryFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_blogCategoryFactory = $blogCategoryFactory;
        $this->_objectManager = $objectManager;
    }

    public function getData()
    {
        $data = [];
        try {
            $collection = $this->_blogCategoryFactory->create();
            $data = $collection->getData();

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $data;
    }

    public function insertBlogCategory($data)
    {
        if (!is_array($data)) {
            throw new LocalizedException(__('Invalid data format.'));
        }
        try {
            $category = $this->_objectManager->create('Tigren\SimpleBlog\Model\Category');
            $category->setData($data)->save();

            // Trả về dữ liệu blog category vừa tạo
            return $category->getData();
        } catch (\Exception $e) {
            throw new LocalizedException(__('Unable to save Blog Category: ' . $e->getMessage()));
        }
    }

    public function updateBlogCategory($id, $data)
    {
        if (!is_array($data)) {
            throw new LocalizedException(__('Invalid data format.'));
        }
        $category = $this->_objectManager->create('Tigren\SimpleBlog\Model\Category')->load($id);
        if (!$category->getId()) {
            throw new GraphQlNoSuchEntityException(__('Blog Category with ID "%1" does not exist.', $id));
        }
        $category->addData($data)->save();
        return $category->getData();
        //        return ['message' => 'Blog Category updated successfully'];
    }

    public function deleteBlogCategory($id)
    {
        $category = $this->_objectManager->create('Tigren\SimpleBlog\Model\Category')->load($id);
        if (!$category->getId()) {
            throw new GraphQlNoSuchEntityException(__('Blog Category with ID "%1" does not exist.', $id));
        }
        $category->delete();

        // Trả về thông báo sau khi xóa
        return ['message' => 'Blog Category deleted successfully'];
    }
}