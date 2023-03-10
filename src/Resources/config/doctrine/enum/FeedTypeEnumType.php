<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;

final class FeedTypeEnumType extends AbstractEnumType
{
    public const NAME = 'feed_type';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = $this->checkEnumValue($value);

        return FeedType::tryFrom($value);
    }
}
