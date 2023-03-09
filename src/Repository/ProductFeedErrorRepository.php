<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ProductFeedErrorRepository extends EntityRepository implements ProductFeedErrorRepositoryInterface
{
    public function getFeedErrors(int | string $feedId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.productFeed = :feedId')
            ->setParameter('feedId', (int)$feedId);
    }
}
