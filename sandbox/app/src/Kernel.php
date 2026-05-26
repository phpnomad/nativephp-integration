<?php

namespace SandboxApp;

use PHPNomad\Component\JsonConfigIntegration\ConfigInitializer;
use PHPNomad\Di\Container\Container;
use PHPNomad\Di\Interfaces\InstanceProvider;
use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\FastRoute\Component\RestInitializer;
use PHPNomad\Loader\Bootstrapper;
use PHPNomad\NativePHP\Integration\Initializer as NativePHPInitializer;
use PHPNomad\Symfony\Component\Console\Initializer as SymfonyConsoleInitializer;
use PHPNomad\Symfony\Component\EventDispatcherIntegration\Initializer as EventInitializer;

/**
 * One-call wiring of the entire stack. Used by both the CLI entry point
 * (bin/nomad) and the HTTP front controller (public/index.php).
 *
 * Order of Initializers matters: the integrations that provide concrete
 * bindings (Config, Event, Console) must load before the integration that
 * uses them (NativePHP) and the app's own initializer.
 */
final class Kernel
{
    public static function boot(): InstanceProvider
    {
        $container = new Container();
        // The container has to be resolvable as InstanceProvider so other
        // strategies (notably fastroute's WebRoutesRegistry) can inject it.
        $container->bindFactory(InstanceProvider::class, fn () => $container);

        $bootstrapper = new Bootstrapper(
            $container,
            new ContextInitializer(),
            new ConfigInitializer([
                'nativephp' => __DIR__ . '/../config/nativephp.json',
            ]),
            new EventInitializer(),
            new SymfonyConsoleInitializer(),
            new RestInitializer(),
            new NativePHPInitializer(),
            new AppInitializer(),
        );
        $bootstrapper->load();

        return $container;
    }
}
