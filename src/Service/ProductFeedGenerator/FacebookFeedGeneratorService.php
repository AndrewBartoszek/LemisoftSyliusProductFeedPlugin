<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FacebookFeedItemModelAbstract;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FacebookFeedXmlModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FeedItemModelInterface;
use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FacebookFeedGeneratorService extends AbstractBaseFeedGenerator
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

            $xmlModel = new FacebookFeedXmlModel($items, $this->getProductFeed());
            $domainUrl = $this->getDomainUrl();
            if (null !== $domainUrl) {
                $xmlModel->setShopLink($domainUrl);
            }

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
        /** @var ProductVariant $variant */
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
        $model = (new FacebookFeedItemModelAbstract())->fromVariant($variant, $this->getProductFeed());
        $model->setProductLink($this->prepareLink($product));
        $model->setPrice($this->getGoogleTypePrice($variant));
        $this->setImagesUrls($product, $variant, $model);

        $errors = $this->validate($model);
        if (null !== $errors) {
            $this->saveErrors($errors, $model);
        }

        return $model;
    }
}
