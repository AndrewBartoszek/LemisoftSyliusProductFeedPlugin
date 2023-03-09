<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedItemModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedXmlModel;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FeedItemModelInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CeneoFeedGeneratorService extends AbstractBaseFeedGenerator
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
        try{
            $this->clearErrors();
            $items = $this->prepareItems();

            $xmlModel = new CeneoFeedXmlModel($items);

            $xml = $this->serializer->serialize($xmlModel, 'xml');

            $this->saveXmlFile($xml);
            $status = true;
        } catch(Exception $e){
            $status = false;
        }

        $this->saveFeedGenerationStatus($status);
    }

    /**
     * @return FeedItemModelInterface[]
     */
    protected function processProduct(Product $product): array
    {
        $items = [];
        /** @var ProductVariant $variant */
        foreach ($product->getVariants() as $variant) {
            //jezeli śledzony i nie ma go na stanie to pomijamy
            if ($variant->isTracked() && !$variant->isInStock()) {
                continue;
            }

            $model = (new CeneoFeedItemModel())->fromVariant($variant, $this->getProductFeed());
            $model->setProductLink($this->prepareLink($product));
            $model->setPrice($this->getPrice($variant));

            $this->setImagesUrls($product, $variant, $model);

            $errors = $this->validate($model);
            if (null !== $errors) {
                $this->saveErrors($errors, $model);
            }

            $items[] = $model;
        }

        return $items;
    }

    protected function getPrice(ProductVariantInterface $variant): ?string
    {
        $price = $this->productVariantPricesCalculator->calculate($variant, ['channel' => $this->getChannel()]);
        $penny = sprintf("%02d", $price % 100);
        $total = (int)($price / 100);

        $currencyCode = $this->getChannel()->getBaseCurrency()?->getCode();

        return null === $currencyCode ? null : $total . '.' . $penny;
    }
}
