<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

trait ProductRepositoryTrait
{
    /**
     * @return ProductInterface[]
     */
    public function getProductToGenerateFeed(ChannelInterface $channel): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = :enabled')
            ->setParameter('channel', $channel)
            ->setParameter('enabled', true)
            ->getQuery()
            ->getResult();
    }
}
