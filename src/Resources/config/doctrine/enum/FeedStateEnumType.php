<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedStateType;

final class FeedStateEnumType extends AbstractEnumType
{
    public const NAME = 'feed_state';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return FeedStateType::NEW;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = $this->checkEnumValue($value);

        return FeedStateType::tryFrom($value);
    }
}
