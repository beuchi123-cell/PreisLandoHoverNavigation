<?php

namespace PreisLandoHoverNavigation\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\ApiRouter;
use Plenty\Plugin\Routing\Router;

class HoverNavigationRouteServiceProvider extends RouteServiceProvider
{
    public function map(
        Router $router,
        ApiRouter $api
    ): void
    {
        $api->version(
            ['v1'],
            [
                'namespace' => 'PreisLandoHoverNavigation\Api\Resources'
            ],
            function (ApiRouter $api)
            {
                $api->get(
                    'preislando/search-price/{variationId}',
                    'SearchPriceResource@show'
                );
            }
        );
    }
}
