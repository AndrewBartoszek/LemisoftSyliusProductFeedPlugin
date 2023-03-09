<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\EventListener;

use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed\AvailableProductFeedTypeService;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class MenuBuilderListener
{
    public function __construct(private AvailableProductFeedTypeService $availableProductFeedTypeService)
    {
    }

    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $marketing = $menu->getChild('marketing');

        if (
            null !== $marketing && $this->availableProductFeedTypeService->hasAvailable()
        ) {
            $marketing
                ->addChild(
                    'lemisoft_product_feed',
                    ['route' => 'lemisoft_sylius_product_feeds_plugin_admin_product_feed_index'],
                )
                ->setLabel('lemisoft_sylius_product_feeds_plugin.admin.menu.product_feed')
                ->setLabelAttribute('icon', 'magnify');
        }
    }
}
