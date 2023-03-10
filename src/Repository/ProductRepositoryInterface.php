<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface ProductRepositoryInterface
{
    /**
     * @return ProductInterface[]
     */
    public function getProductToGenerateFeed(ChannelInterface $channel): array;
}
