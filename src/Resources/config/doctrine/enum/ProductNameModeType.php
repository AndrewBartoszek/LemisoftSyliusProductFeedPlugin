<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductNameModeType as ProdNameModeType;

class ProductNameModeType extends AbstractEnumType
{
    public const NAME = 'product_name_mode';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return ProdNameModeType::ONLY_PRODUCT_NAME;
    }

    public static function getEnumsClass(): string
    {
        return ProdNameModeType::class;
    }
}
