<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;

interface ProductFeedRepositoryInterface
{
    /**
     * @param FeedType[] $availableProductFeeds
     */
    public function getAvailableProductFeeds(array $availableProductFeeds): QueryBuilder;

    /**
     * @param FeedType[] $availableProductFeeds
     */
    public function findProductFeedToGenerate(array $availableProductFeeds, int $id): ?ProductFeedInterface;
}
