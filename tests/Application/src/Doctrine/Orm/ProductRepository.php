<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusProductFeedsPlugin\Application\src\Doctrine\Orm;

use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository
{
    use ProductRepositoryTrait;
}
