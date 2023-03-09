<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusProductFeedsPlugin\Controller\Admin\ProductFeedController;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();
    $services
        ->defaults()
        ->autoconfigure(true)
        ->autowire(true);
    $services
        ->set('lemisoft.sylius_product_feeds_plugin.controller.admin.product_feed_controller', ProductFeedController::class)
        ->args([
            service('lemisoft.sylius_product_feeds_plugin.service.product_feed.product_feed_service'),

        ])
        ->tag('controller.service_arguments');
};
