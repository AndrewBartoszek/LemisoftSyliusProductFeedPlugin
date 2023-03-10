<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\Facebook;

enum GoogleAvailabilityType: string
{
    case IN_STOCK = 'in stock';
    case AVAILABLE_FOR_ORDER = 'available for order';
    case OUT_OF_STOCK = 'out of stock';
}
