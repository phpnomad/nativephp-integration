<?php

namespace SandboxApp;

use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Interfaces\HasBindings;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Events\Interfaces\HasListeners;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Loader\Interfaces\Loadable;
use PHPNomad\NativePHP\Integration\DataObjects\WindowDefinition;
use PHPNomad\NativePHP\Integration\Events\AppBooted;
use PHPNomad\NativePHP\Integration\Events\WindowFocused;
use PHPNomad\NativePHP\Integration\WindowManager;
use SandboxApp\Listeners\LogEvents;
use SandboxApp\Listeners\NotifyOnFocus;

/**
 * The PoC app's own Initializer. Wires:
 *   • A "main" window definition (URL points to our local PHP server)
 *   • A listener that logs every booted/focused/etc. event
 *   • A listener that fires a notification back through the integration
 *     whenever the window gains focus
 */
class AppInitializer implements
    HasClassDefinitions,
    Loadable,
    CanSetContainer,
    HasListeners
{
    use HasSettableContainer;

    public function getClassDefinitions(): array
    {
        return [];
    }

    public function getListeners(): array
    {
        return [
            AppBooted::class => [LogEvents::class],
            WindowFocused::class => [LogEvents::class, NotifyOnFocus::class],
        ];
    }

    public function load(): void
    {
        if (! $this->container instanceof HasBindings) {
            return;
        }

        // Register the app's main window with the integration's WindowManager.
        // openOnBoot is false here because Electron-side main.js opens the
        // window itself once both PHP and the API server are up (we can't
        // open until both ports are live).
        $this->container->get(WindowManager::class)->register(
            new WindowDefinition(
                id: 'main',
                url: 'http://127.0.0.1:' . (getenv('PHP_SERVER_PORT') ?: '8100') . '/',
                title: 'PHPNomad × NativePHP',
                width: 900,
                height: 700,
                openOnBoot: false,
            )
        );
    }
}
