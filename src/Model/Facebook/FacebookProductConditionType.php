<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\Facebook;

enum FacebookProductConditionType: string
{
    case NEW = 'new';
    case REFURBISHED = 'refurbished';
    case USED = 'used';
}
