<?php

declare(strict_types=1);

namespace Tigren\CustomerGroupCatalog\ViewModel;

//use Codeception\Util\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleHistory\Collection;
use Tigren\CustomerGroupCatalog\Service\RuleHistoryProvider;
use Tigren\CustomerGroupCatalog\Model\RuleHistory;
use Magento\Framework\App\RequestInterface;
use Magento\Theme\Block\Html\Pager;
use Magento\Framework\View\Element\Template;

class RuleHistoryViewModel implements ArgumentInterface
{
    public function __construct(
        private RuleHistoryProvider $ruleHistoryProvider,
        private RequestInterface $request,
        //        private UrlInterface $url,
        //        private StoreManagerInterface $storeManager
    )
    {

    }

    public function getRuleHistory(int $limit): Collection
    {
        return $this->ruleHistoryProvider->getRuleHistory($limit, $this->getCurrentPage());
    }

    private function getCurrentPage(): int
    {
        return (int)$this->request->getParam('p', 1);
    }

    public function getPager(Collection $collection, Pager $pagerBlock): string
    {
        $pagerBlock->setUseContainer(false)
            ->setShowPerPage(false)
            ->setShowAmount(false)
            ->setFrameLength(3)
            ->setLimit($collection->getPageSize())
            ->setCollection($collection);

        return $pagerBlock->toHtml();
    }

    public function getRuleHistoryHtml(Template $block, RuleHistory $ruleHistory): string
    {
        $block->setData('ruleHistory', $ruleHistory);
        return $block->toHtml();
    }
}
