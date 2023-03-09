<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model;

enum ProductNameModeType: string
{
    case ONLY_PRODUCT_NAME = 'only_product_name';
    case PRODUCT_AND_VARIANT_NAME = 'product_and_variant_name';

    public static function getAvailableToFormSelect(): array
    {
        $result = [];
        foreach (ProductNameModeType::cases() as $enum) {
            $result['lemisoft_sylius_product_feeds_plugin.product_feed.product_name_mode.' . $enum->value] = $enum->value;
        }

        return $result;
    }
}
