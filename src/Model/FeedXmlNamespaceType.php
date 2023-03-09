<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model;

enum FeedXmlNamespaceType: string
{
    case FACEBOOK = 'http://base.google.com/ns/1.0';
    case CENEO = 'http://www.w3.org/2001/XMLSchema-instance';
}
