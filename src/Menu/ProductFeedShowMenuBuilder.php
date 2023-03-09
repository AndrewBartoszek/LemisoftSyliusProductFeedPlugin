<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Event\ProductFeedShowMenuBuilderEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ProductFeedShowMenuBuilder
{
    public function __construct(
        private FactoryInterface $factory,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function createMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!isset($options['feed'])) {
            return $menu;
        }

        $feed = $options['feed'];

        if (!$feed instanceof ProductFeedInterface) {
            return $menu;
        }

        $menu
            ->addChild('generate', [
                'route'           => 'lemisoft_sylius_product_feeds_plugin_admin_product_feed_generate',
                'routeParameters' => ['id' => $feed->getId()],
            ])
            ->setAttribute('type', 'link')
            ->setLabel('lemisoft_sylius_product_feeds_plugin.admin.product_feed.generate')
            ->setLabelAttribute('icon', 'redo');


        $menu
            ->addChild('edit', [
                'route'           => 'lemisoft_sylius_product_feeds_plugin_admin_product_feed_update',
                'routeParameters' => ['id' => $feed->getId()],
            ])
            ->setAttribute('type', 'link')
            ->setLabel('sylius.ui.edit')
            ->setLabelAttribute('icon', 'pencil');

        $this->eventDispatcher->dispatch(new ProductFeedShowMenuBuilderEvent($this->factory, $menu, $feed));

        return $menu;
    }
}
