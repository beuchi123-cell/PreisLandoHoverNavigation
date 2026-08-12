<?php

namespace PreisLandoHoverNavigation\Providers;

use IO\Helper\ResourceContainer;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;

class HoverNavigationServiceProvider extends ServiceProvider
{
    const PRIORITY = 0;

    public function register()
    {
    }

    public function boot(Twig $twig, Dispatcher $eventDispatcher)
    {
        $eventDispatcher->listen(
            "IO.Resources.Import",
            function (ResourceContainer $container)
            {
                $container->addScriptTemplate(
                    "PreisLandoHoverNavigation::content.HoverNavigationScript"
                );

                $container->addStyleTemplate(
                    "PreisLandoHoverNavigation::content.HoverNavigationStyle"
                );
            },
            self::PRIORITY
        );
    }
}
