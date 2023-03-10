<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator\CeneoFeedGeneratorService;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator\FacebookFeedGeneratorService;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator\GoogleFeedGeneratorService;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeedGenerator\ProductFeedGeneratorFactory;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();
    $services
        ->defaults()
        ->autoconfigure(true)
        ->autowire(true);

    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.product_feed_generator_factory',
            ProductFeedGeneratorFactory::class,
        )->args([
            service(
                'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.ceneo_feed_generator_service',
            ),
            service(
                'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.facebook_feed_generator_service',
            ),
            service(
                'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.google_feed_generator_service',
            ),
        ]);

    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.ceneo_feed_generator_service',
            CeneoFeedGeneratorService::class,
        )->args([
            service('sylius.repository.product'),
            service('doctrine.orm.entity_manager'),
            service('jms_serializer.serializer'),
            service('router.default'),
            service('sylius.calculator.product_variant_price'),
            service('liip_imagine.cache.manager'),
            service('validator'),
            '%kernel.project_dir%',
        ]);
    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.facebook_feed_generator_service',
            FacebookFeedGeneratorService::class,
        )->args([
            service('sylius.repository.product'),
            service('doctrine.orm.entity_manager'),
            service('jms_serializer.serializer'),
            service('router.default'),
            service('sylius.calculator.product_variant_price'),
            service('liip_imagine.cache.manager'),
            service('validator'),
            '%kernel.project_dir%',
        ]);
    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.service.product_feed_generator.google_feed_generator_service',
            GoogleFeedGeneratorService::class,
        )->args([
            service('sylius.repository.product'),
            service('doctrine.orm.entity_manager'),
            service('jms_serializer.serializer'),
            service('router.default'),
            service('sylius.calculator.product_variant_price'),
            service('liip_imagine.cache.manager'),
            service('validator'),
            '%kernel.project_dir%',
        ]);
};
