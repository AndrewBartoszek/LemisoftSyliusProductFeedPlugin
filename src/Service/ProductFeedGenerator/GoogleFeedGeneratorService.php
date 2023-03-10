<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FeedItemModelInterface;
use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GoogleFeedGeneratorService extends AbstractBaseFeedGenerator
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected UrlGeneratorInterface $urlGenerator,
        protected ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        protected CacheManager $imagineCacheManager,
        protected ValidatorInterface $validator,
        protected string $projectDir,
    ) {
    }

    public function generate(): void
    {
    }

    /**
     * @return FeedItemModelInterface[]
     */
    protected function processProduct(ProductInterface $product): array
    {
        $items = [];
        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants() as $variant) {
            //jezeli śledzony i nie ma go na stanie to pomijamy
            if ($variant->isTracked() && !$variant->isInStock()) {
                continue;
            }

//            $items[] = $this->processVariant($product, $variant);
        }

        return $items;
    }
}
