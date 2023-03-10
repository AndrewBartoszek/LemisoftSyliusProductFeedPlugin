<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Symfony\Component\HttpFoundation\File\File;

interface BaseFeedGeneratorInterface
{
    public function init(ProductFeedInterface $productFeed): BaseFeedGeneratorInterface;

    public function generate(): void;

    public function getXmlFile(): File;
}
