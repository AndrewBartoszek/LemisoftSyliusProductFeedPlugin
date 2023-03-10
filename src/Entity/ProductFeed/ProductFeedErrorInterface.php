<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed;

interface ProductFeedErrorInterface
{
    public function getId(): ?int;

    public function getMessage(): ?string;

    public function setMessage(?string $message): void;

    public function getData(): ?string;

    public function setData(?string $data): void;

    public function getProductFeed(): ?ProductFeedInterface;

    public function setProductFeed(?ProductFeedInterface $productFeed): void;
}
