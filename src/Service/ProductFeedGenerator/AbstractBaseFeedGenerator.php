<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedError;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedStateType;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FeedItemModelInterface;
use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductImage;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractBaseFeedGenerator implements BaseFeedGeneratorInterface
{
    protected const HTTPS_PROTOCOL = 'https';
    protected const HTTP_PROTOCOL = 'http';
    protected const PRODUCT_DETAIL_SHOP_ROUTE = 'sylius_shop_product_show';
    protected const DEFAULT_IMAGINE_FILTER = 'sylius_shop_product_original';
    protected const FEED_DIR = 'etc' . DIRECTORY_SEPARATOR . 'feed';
    protected const PENNY_VALUE = 100;
    protected const WEB_URL_SEPARATOR = '/';
    protected const ZERO_INT = 0;

    protected ?ProductFeedInterface $productFeed = null;
    protected ?ChannelInterface $channel = null;

    protected ProductRepositoryInterface $productRepository;
    protected EntityManagerInterface $em;
    protected SerializerInterface $serializer;
    protected UrlGeneratorInterface $urlGenerator;
    protected ProductVariantPricesCalculatorInterface $productVariantPricesCalculator;
    protected CacheManager $imagineCacheManager;
    protected ValidatorInterface $validator;
    protected string $projectDir;

    public function init(ProductFeedInterface $productFeed): BaseFeedGeneratorInterface
    {
        $this->productFeed = $productFeed;
        $this->channel = $this->getChannel();

        return $this;
    }

    /**
     * @return FeedItemModelInterface[]
     *
     * @throws Exception
     */
    public function prepareItems(): array
    {
        $products = $this->getData();

        return $this->processProducts($products);
    }

    public function getXmlFile(): File
    {
        return new File($this->getFilePath());
    }

    protected function getFilePath(): string
    {
        $productFeedCode = $this->getProductFeed()->getCode();
        if (null === $productFeedCode) {
            throw new Exception('Niepoprawnie utworzony feed. Feed musi posiadać kod');
        }

        return $this->projectDir . DIRECTORY_SEPARATOR . self::FEED_DIR . DIRECTORY_SEPARATOR . $productFeedCode . '.xml';
    }

    /**
     * @return FeedItemModelInterface[]
     */
    abstract protected function processProduct(ProductInterface $product): array;

    protected function saveXmlFile(string $xml): void
    {
        file_put_contents(
            $this->getFilePath(),
            $xml,
        );
    }

    /**
     * @return ProductInterface[]
     */
    protected function getData(): array
    {
        return $this->productRepository->getProductToGenerateFeed($this->getChannel());
    }

    protected function getProductFeed(): ProductFeedInterface
    {
        return null !== $this->productFeed ? $this->productFeed : throw new Exception(
            'Niezainicjowano generatora feed-ow',
        );
    }

    /**
     * @throws Exception
     */
    protected function getChannel(): ChannelInterface
    {
        return $this->getProductFeed()->getChannel() ?? throw new Exception('Feed nie ma przypisanego kanału');
    }

    /**
     * @param ProductInterface[] $products
     *
     * @return FeedItemModelInterface[]
     */
    protected function processProducts(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $items = $this->processProduct($product);
            $result = array_merge($result, $items);
        }

        return $result;
    }

    protected function prepareLink(ProductInterface $product): ?string
    {
        $domainUrl = $this->getDomainUrl();
        if (null === $domainUrl) {
            return null;
        }

        return $domainUrl .
            $this->urlGenerator->generate(
                self::PRODUCT_DETAIL_SHOP_ROUTE,
                [
                    'slug'    => $product->getSlug(),
                    '_locale' => $this->getChannel()->getDefaultLocale()?->getCode(),
                ],
            );
    }

    protected function getDomainUrl(): ?string
    {
        $hostname = $this->getChannel()->getHostname();

        if (null === $hostname) {
            return null;
        }

        $hostName = rtrim($hostname, '/');

        return ($this->getProductFeed()->isHttps() ? self::HTTPS_PROTOCOL : self::HTTP_PROTOCOL) . '://' . $hostName;
    }

    protected function setImagesUrls(
        ProductInterface $product,
        ProductVariantInterface $variant,
        FeedItemModelInterface $model,
    ): void {

        $urls = $this->getImagesUrls($product, $variant);
        if (isset($urls[0])) {
            $model->setImage($urls[0]);
            unset($urls[0]);
        }

        foreach ($urls as $url) {
            $model->addAdditionalImage($url);
        }
    }

    /**
     * @return string[]
     */
    protected function getImagesUrls(ProductInterface $product, ProductVariantInterface $variant): array
    {
        $imageUrls = $this->getImages($product);

        $images = [];
        foreach ($imageUrls as $imageUrl) {
            $images[] = $imageUrl;
        }

        return $images;
    }

    /**
     * @return string[]
     */
    protected function getImages(ProductInterface $product): array
    {
        $urls = [];
        /** @var ProductImage $productImage */
        foreach ($product->getImages() as $productImage) {
            $imagePath = $productImage->getPath();
            $domainUrl = $this->getDomainUrl();
            if (null === $imagePath || null === $domainUrl) {
                continue;
            }
            /** @var string $parseUrl */
            $parseUrl = parse_url($imagePath, PHP_URL_PATH);

            $url = $this->imagineCacheManager->generateUrl(
                $parseUrl,
                self::DEFAULT_IMAGINE_FILTER,
                [],
                null,
                UrlGeneratorInterface::ABSOLUTE_PATH,
            );

            $urls[] = rtrim($domainUrl, self::WEB_URL_SEPARATOR) .
                self::WEB_URL_SEPARATOR .
                ltrim($url, self::WEB_URL_SEPARATOR);
        }

        return $urls;
    }

    protected function getGoogleTypePrice(ProductVariantInterface $variant): ?string
    {
        $price = $this->productVariantPricesCalculator->calculate($variant, ['channel' => $this->getChannel()]);
        $penny = sprintf("%02d", $price % self::PENNY_VALUE);
        $total = (int)($price / self::PENNY_VALUE);

        $currencyCode = $this->getChannel()->getBaseCurrency()?->getCode();

        return null === $currencyCode ? null : $total . '.' . $penny . ' ' . $currencyCode;
    }

    protected function saveFeedGenerationStatus(bool $status): void
    {
        $productFeed = $this->getProductFeed();
        if ($status) {
            $productFeed->setState(FeedStateType::READY);
        } else {
            $productFeed->setState(FeedStateType::ERROR);
        }

        $this->em->persist($productFeed);
        $this->em->flush();
    }

    /**
     * @param string[] $validationGroups
     */
    protected function validate(
        FeedItemModelInterface $model,
        array $validationGroups = [],
    ): ?ConstraintViolationListInterface {
        $errors = $this->validator->validate($model, null, $validationGroups);

        if (self::ZERO_INT < count($errors)) {
            return $errors;
        }

        return null;
    }

    protected function clearErrors(): void
    {
        /** @var ProductFeedError[] $errors */
        $errors = $this->getProductFeed()->getProductFeedErrors();

        foreach ($errors as $error) {
            $this->em->remove($error);
        }

        $this->em->flush();
    }

    protected function saveErrors(ConstraintViolationListInterface $errors, FeedItemModelInterface $model): void
    {
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorEntity = new ProductFeedError();
            $errorEntity->setProductFeed($this->getProductFeed());
            $errorEntity->setData($this->serializer->serialize($model, 'json'));
            $errorEntity->setMessage($error->getPropertyPath() . ':' . $error->getMessage());

            $this->em->persist($errorEntity);
        }

        $this->em->flush();
    }
}
