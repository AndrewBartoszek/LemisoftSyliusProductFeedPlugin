<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class ProductFeedShowMenuBuilderEvent extends MenuBuilderEvent
{
    public function __construct(FactoryInterface $factory, ItemInterface $menu, private ProductFeedInterface $feed)
    {
        parent::__construct($factory, $menu);
    }

    public function getFeed(): ProductFeedInterface
    {
        return $this->feed;
    }
}
