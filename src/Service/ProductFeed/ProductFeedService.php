<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed;

use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;
use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductFeedRepository;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator\ProductFeedGeneratorFactory;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;

final class ProductFeedService
{
    /**
     * @param ProductFeedRepository $repository
     */
    public function __construct(
        protected RepositoryInterface $repository,
        protected AvailableProductFeedTypeService $availableProductFeedTypeService,
        protected ProductFeedGeneratorFactory $productFeedGeneratorFactory,
    ) {
    }

    public function findProductFeedToGenerate(int $id): ?ProductFeedInterface
    {
        return $this->repository->findProductFeedToGenerate(
            $this->availableProductFeedTypeService->getAvailableProductFeeds(),
            $id,
        );
    }

    public function findProductFeedByCode(string $code): ?ProductFeedInterface
    {
        return $this->repository->findProductFeedByCode(
            $this->availableProductFeedTypeService->getAvailableProductFeeds(),
            $code,
        );
    }

    public function generate(ProductFeedInterface $productFeed): void
    {
        $generator = $this->productFeedGeneratorFactory->getGenerator($productFeed);
        $generator->generate();
    }

    /**
     * @return FeedType[]
     */
    public function getAvailableProductFeeds(): array
    {
        return $this->availableProductFeedTypeService->getAvailableProductFeeds();
    }

    public function getXmlFile(ProductFeedInterface $productFeed): File
    {
        $generator = $this->productFeedGeneratorFactory->getGenerator($productFeed);

        return $generator->getXmlFile();
    }
}
