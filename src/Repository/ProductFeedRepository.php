<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class ProductFeedRepository extends EntityRepository implements ProductFeedRepositoryInterface
{
    /**
     * @param FeedType[] $availableProductFeeds
     */
    public function getAvailableProductFeeds(array $availableProductFeeds): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.feedType in (:availableProductFedsType)')
            ->setParameter('availableProductFedsType', $availableProductFeeds);
    }

    /**
     * @param FeedType[] $availableProductFeeds
     *
     * @throws NonUniqueResultException
     */
    public function findProductFeedToGenerate(array $availableProductFeeds, int $id): ?ProductFeedInterface
    {
        /** @var ProductFeedInterface|null $result */
        $result =  $this->createQueryBuilder('o')
            ->andWhere('o.id = :feedId')
            ->andWhere('o.feedType in (:availableProductFedsType)')
            ->setParameter('availableProductFedsType', $availableProductFeeds)
            ->setParameter('feedId', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    /**
     * @param FeedType[] $availableProductFeeds
     *
     * @throws NonUniqueResultException
     */
    public function findProductFeedByCode(array $availableProductFeeds, string $code): ?ProductFeedInterface
    {
        /** @var ProductFeedInterface|null $result */
        $result =  $this->createQueryBuilder('o')
            ->andWhere('o.code = :feedCode')
            ->andWhere('o.feedType in (:availableProductFedsType)')
            ->setParameter('availableProductFedsType', $availableProductFeeds)
            ->setParameter('feedCode', $code)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}
