<?php

namespace Tigren\CustomerGroupCatalog\Ui\Component\Listing\Grid\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Action extends Column
{
    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['rule_id'])) {
                    // Lấy tên từ item hoặc dùng một giá trị mặc định nếu không có
                    $name = isset($item['name']) ? $item['name'] : __('Item');

                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                'customer_group_catalog/rule/edit',
                                ['id' => $item['rule_id']]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                'customer_group_catalog/rule/delete',
                                ['id' => $item['rule_id']]
                            ),

                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $name),
                                'message' => __('Are you sure you want to delete the record named "%1"?', $name)
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
