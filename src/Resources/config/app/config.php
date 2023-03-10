<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusProductFeedsPlugin\EventListener\MenuBuilderListener;
use Lemisoft\SyliusProductFeedsPlugin\Form\ProductFeed\ProductFeedType;
use Lemisoft\SyliusProductFeedsPlugin\Menu\ProductFeedShowMenuBuilder;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed\ProductFeedService;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import(
        '@LemisoftSyliusProductFeedsPlugin/src/Resources/config/sylius/sylius_resource.yaml',
    );
    $containerConfigurator->import(
        '@LemisoftSyliusProductFeedsPlugin/src/Resources/config/sylius/sylius_grid.yaml',
    );
    $containerConfigurator->import(
        '@LemisoftSyliusProductFeedsPlugin/src/Resources/config/app/controller.php',
    );
    $containerConfigurator->import(
        '@LemisoftSyliusProductFeedsPlugin/src/Resources/config/app/generator.php',
    );

    $services = $containerConfigurator->services();
    $services
        ->set('lemisoft.sylius_product_feeds_plugin.form.product_feed.product_feed_type', ProductFeedType::class)
        ->args([
            service('lemisoft.sylius_product_feeds_plugin.service.product_feed.available_product_feed_type_service'),
            '%lemisoft_sylius_product_feeds_plugin.model.product_feed.class%',
            ['Default', 'sylius'],
        ])
        ->tag('form.type');

    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.service.product_feed.product_feed_service',
            ProductFeedService::class,
        )->args([
            service('lemisoft_sylius_product_feeds_plugin.repository.product_feed'),
            service('lemisoft.sylius_product_feeds_plugin.service.product_feed.available_product_feed_type_service'),
            service('lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.product_feed_generator_factory'),
        ])
        ->public();

    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.event_listener.menu_builder_listener',
            MenuBuilderListener::class,
        )
        ->args(
            [service('lemisoft.sylius_product_feeds_plugin.service.product_feed.available_product_feed_type_service')],
        )
        ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.main', 'method' => 'addAdminMenuItems']);

    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.menu.product_feed_show_menu_builder',
            ProductFeedShowMenuBuilder::class,
        )
        ->args([
            service('knp_menu.factory'),
            service('event_dispatcher'),
        ])
        ->tag(
            'knp_menu.menu_builder',
            [
                'alias' => 'lemisoft.sylius_product_feeds_plugin.menu.product_feed_show_menu_builder',
                'method' => 'createMenu',
            ],
        );
};
