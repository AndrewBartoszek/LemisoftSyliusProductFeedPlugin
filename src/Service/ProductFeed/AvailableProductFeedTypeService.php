<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed;

use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;

final class AvailableProductFeedTypeService
{
    public const NO_AVAILABLE_PROD_FEEDS_NUMBER = 0;

    /**
     * @param string[] $productFeedAvailable
     */
    public function __construct(private array $productFeedAvailable = [])
    {
    }

    /**
     * @return FeedType[]
     */
    public function getAvailableProductFeeds(): array
    {
        $result = [];
        foreach ($this->productFeedAvailable as $availableTypeCode) {
            $type = FeedType::tryFrom($availableTypeCode);
            if (null !== $type) {
                $result[] = $type;
            }
        }

        return $result;
    }

    public function hasAvailable(): bool
    {
        return self::NO_AVAILABLE_PROD_FEEDS_NUMBER < count($this->getAvailableProductFeeds());
    }

    public function getAvailableToFormSelect(): array
    {
        $available = $this->getAvailableProductFeeds();
        $result = [];
        foreach ($available as $enum) {
            $result['lemisoft_sylius_product_feeds_plugin.product_feed.type.' . $enum->value] = $enum->value;
        }

        return $result;
    }
}
