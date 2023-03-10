<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\Facebook;

enum GoogleProductConditionType: string
{
    case NEW = 'new';
    case REFURBISHED = 'refurbished';
    case USED = 'used';
}
