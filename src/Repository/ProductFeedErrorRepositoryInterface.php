<?php

namespace Lemisoft\SyliusProductFeedsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;

interface ProductFeedErrorRepositoryInterface
{
    public function getFeedErrors(int $feedId): QueryBuilder;
}
