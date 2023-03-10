<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedItemModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedXmlModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FeedItemModelInterface;
use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CeneoFeedGeneratorService extends AbstractBaseFeedGenerator
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
        try {
            $this->clearErrors();
            $items = $this->prepareItems();

            $xmlModel = new CeneoFeedXmlModel($items);

            $xml = $this->serializer->serialize($xmlModel, 'xml');

            $this->saveXmlFile($xml);
            $status = true;
        } catch (Exception $e) {
            $status = false;
        }

        $this->saveFeedGenerationStatus($status);
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

            $items[] = $this->processVariant($product, $variant);
        }

        return $items;
    }

    protected function processVariant(
        ProductInterface $product,
        ProductVariantInterface $variant,
    ): FeedItemModelInterface {
        $model = (new CeneoFeedItemModel())->fromVariant($variant, $this->getProductFeed());
        $model->setProductLink($this->prepareLink($product));
        $model->setPrice($this->getPrice($variant));

        $this->setImagesUrls($product, $variant, $model);

        $errors = $this->validate($model);
        if (null !== $errors) {
            $this->saveErrors($errors, $model);
        }

        return $model;
    }

    protected function getPrice(ProductVariantInterface $variant): ?string
    {
        $price = $this->productVariantPricesCalculator->calculate($variant, ['channel' => $this->getChannel()]);
        $penny = sprintf("%02d", $price % self::PENNY_VALUE);
        $total = (int)($price / self::PENNY_VALUE);

        $currencyCode = $this->getChannel()->getBaseCurrency()?->getCode();

        return null === $currencyCode ? null : $total . '.' . $penny;
    }
}
