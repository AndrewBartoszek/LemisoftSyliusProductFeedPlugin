<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LogicException;

abstract class AbstractEnumType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'TEXT';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return null;
    }

    public function checkEnumValue(mixed $value): string
    {
        if (!is_string($value)) {
            throw new LogicException("Wartość enuma musi być stringiem");
        }

        return $value;
    }
}
