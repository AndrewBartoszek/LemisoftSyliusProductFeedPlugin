<?php

namespace Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed;

interface ProductFeedErrorInterface
{
    public function getId(): ?int;

    public function getMessage(): ?string;

    public function setMessage(?string $message): void;

    public function getData(): ?string;

    public function setData(?string $data): void;

    public function getProductFeed(): ?ProductFeed;

    public function setProductFeed(?ProductFeed $productFeed): void;
}
