<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed\AvailableProductFeedTypeService;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->parameters()->set('product_feed_available', '%env(json:PRODUCT_FEED_AVAILABLE)%');

    $services = $containerConfigurator->services();
    $services
        ->set(
            'lemisoft.sylius_product_feeds_plugin.service.product_feed.available_product_feed_type_service',
            AvailableProductFeedTypeService::class,
        )
        ->public()
        ->args([param('product_feed_available')]);
};
