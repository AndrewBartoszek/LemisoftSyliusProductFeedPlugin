<?php

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

interface FeedItemModelInterface
{
    public function setImage(string $url): void;

    public function addAdditionalImage(string $url): void;

    public function setProductLink(?string $link): void;

    public function setPrice(?string $price): void;
}
