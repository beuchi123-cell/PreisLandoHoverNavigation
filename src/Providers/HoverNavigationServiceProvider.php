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
        /*
         * Eigenen RouteServiceProvider registrieren.
         * Darüber bauen wir anschließend unseren
         * Preis-Endpunkt für die Schnellsuche.
         */
        $this->getApplication()->register(
            HoverNavigationRouteServiceProvider::class
        );
    }

    public function boot(
        Twig $twig,
        Dispatcher $eventDispatcher
    )
    {
        /*
         * Bestehende Scripts und Styles einbinden.
         * Das bleibt unverändert.
         */
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
