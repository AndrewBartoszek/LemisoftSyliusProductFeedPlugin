<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FacebookFeedItemModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FacebookFeedXmlModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FeedItemModelInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GoogleFeedGeneratorService extends AbstractBaseFeedGenerator
{
    public function __construct(
        protected RepositoryInterface $productRepository,
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected UrlGeneratorInterface $urlGenerator,
        protected ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        protected CacheManager $imagineCacheManager,
        protected ValidatorInterface $validator,
        protected string $projectDir
    ) {
    }

    public function generate(): void
    {

    }
}
