<?php

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;

trait ProductRepositoryTrait
{
    public function getProductToGenerateFeed(ChannelInterface $channel)
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
