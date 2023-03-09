<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed;

use Doctrine\Common\Collections\Collection;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedStateType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductNameModeType;
use Sylius\Component\Core\Model\Channel;

interface ProductFeedInterface
{
    public function getId(): ?int;

    public function getCode(): ?string;

    public function setCode(?string $code): void;

    public function getFeedType(): FeedType;

    public function setFeedType(FeedType $feedType): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getState(): FeedStateType;

    public function setState(FeedStateType $state): void;

    public function getChannel(): ?Channel;

    public function setChannel(?Channel $channel): void;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    public function getProductFeedErrors(): Collection;

    public function getShopName(): ?string;

    public function setShopName(?string $shopName): void;

    public function getShopDescription(): ?string;

    public function setShopDescription(?string $shopDescription): void;

    public function isHttps(): bool;

    public function setIsHttps(bool $isHttps): void;

    public function getProductNameMode(): ProductNameModeType;

    public function setProductNameMode(ProductNameModeType $productNameMode): void;
}
