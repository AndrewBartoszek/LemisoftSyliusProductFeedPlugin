<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductNameModeType;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class AbstractFeedItemModel
{
    const EMPTY_LENGTH = 0;
    const DESCRIPTION_LENGTH = 5000;

    protected function getName(
        ProductInterface $product,
        ProductVariantInterface $variant,
        ProductFeedInterface $productFeed,
    ): ?string {
        return match ($productFeed->getProductNameMode()) {
            ProductNameModeType::ONLY_PRODUCT_NAME => $product->getName(),
            ProductNameModeType::PRODUCT_AND_VARIANT_NAME => $this->getNameFromProductAndVariant($product, $variant)
        };
    }

    protected function getNameFromProductAndVariant(
        ProductInterface $product,
        ProductVariantInterface $variant,
    ): ?string {
        $name = $product->getName();
        $variantName = $variant->getName();

        if (null !== $name) {
            $name = null !== $variantName ? $name . ' ' . $variantName : null;
        }

        return $name;
    }

    protected function getStockCount(ProductVariantInterface $variant): ?int
    {
        return $variant->isTracked() && $variant->isInStock() ? $variant->getOnHand() : null;
    }

    protected function getDescription(ProductInterface $product, ProductVariantInterface $variant): ?string
    {
        if (null !== $product->getDescription()) {
            $description = $product->getDescription();
        } else {
            $description = $product->getShortDescription();
        }

        return null !== $description ? $this->cutDescription($description) : null;
    }

    protected function cutDescription(string $description): string
    {
        if (strlen($description) > self::DESCRIPTION_LENGTH) {
            $desc = strip_tags($description);
            $description = strlen($desc) > self::DESCRIPTION_LENGTH ? substr(
                $desc,
                self::EMPTY_LENGTH,
                self::DESCRIPTION_LENGTH,
            ) : $desc;
        }

        return $description;
    }
}
