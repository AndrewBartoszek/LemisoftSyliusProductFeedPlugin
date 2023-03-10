<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;

final class ProductFeedGeneratorFactory
{
    public function __construct(
        private CeneoFeedGeneratorService $ceneoFeedGenerator,
        private FacebookFeedGeneratorService $facebookFeedGenerator,
        private GoogleFeedGeneratorService $googleFeedGenerator,
    ) {
    }

    public function getGenerator(ProductFeedInterface $productFeed): BaseFeedGeneratorInterface
    {
        return match ($productFeed->getFeedType()) {
            FeedType::CENEO => $this->ceneoFeedGenerator->init($productFeed),
            FeedType::GOOGLE => $this->googleFeedGenerator->init($productFeed),
            FeedType::FACEBOOK => $this->facebookFeedGenerator->init($productFeed),
        };
    }
}
