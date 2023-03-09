<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model;

enum FeedType: string
{
    case GOOGLE = 'google';
    case FACEBOOK = 'facebook';
    case CENEO = 'ceneo';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn (FeedType $enum) => $enum->value,
            FeedType::cases(),
        );
    }
}
