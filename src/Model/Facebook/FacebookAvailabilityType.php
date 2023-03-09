<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\Facebook;

enum FacebookAvailabilityType: string
{
    const IN_STOCK = 'in stock';
    const AVAILABLE_FOR_ORDER = 'available for order';
    const OUT_OF_STOCK = 'out of stock';
}
