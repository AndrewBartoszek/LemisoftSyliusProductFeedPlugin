<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model;

enum FeedStateType: string
{
    case ERROR = 'error';
    case NEW = 'new';
    case READY = 'ready';
}
