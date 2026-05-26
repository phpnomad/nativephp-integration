<?php

namespace PHPNomad\NativePHP\Integration;

use PHPNomad\Console\Interfaces\HasCommands;
use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Interfaces\HasBindings;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Events\Interfaces\HasListeners;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Loader\Interfaces\Loadable;
use PHPNomad\NativePHP\Integration\Client\Client;
use PHPNomad\NativePHP\Integration\Commands\ConfigCommand;
use PHPNomad\NativePHP\Integration\Commands\PhpIniCommand;
use PHPNomad\NativePHP\Integration\Commands\ServeCommand;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;
use PHPNomad\NativePHP\Integration\DataObjects\ClientConfig;
use PHPNomad\NativePHP\Integration\Events\AppBooted;
use PHPNomad\NativePHP\Integration\Http\BootedController;
use PHPNomad\NativePHP\Integration\Http\CookieController;
use PHPNomad\NativePHP\Integration\Http\EventsController;
use PHPNomad\NativePHP\Integration\Interfaces;
use PHPNomad\NativePHP\Integration\Listeners\OpenWindowsOnBoot;
use PHPNomad\NativePHP\Integration\Strategies;
use PHPNomad\Rest\Interfaces\HasControllers;

class Initializer implements
    HasClassDefinitions,
    Loadable,
    CanSetContainer,
    HasControllers,
    HasCommands,
    HasListeners
{
    use HasSettableContainer;

    public function getClassDefinitions(): array
    {
        return [
            // Transport — consumers can swap this with their own NativeClient.
            Client::class => NativeClient::class,

            // Feature strategies — each one is independently swappable. The
            // default bindings here use the Electron-via-HTTP implementations
            // that ship with this package; consumers can rebind any of them
            // (a mock for tests, a logger-backed notifier, an xclip-backed
            // clipboard, an in-memory settings store, etc.).
            Strategies\AlertStrategy::class => Interfaces\AlertStrategy::class,
            Strategies\ClipboardStrategy::class => Interfaces\ClipboardStrategy::class,
            Strategies\ContextMenuStrategy::class => Interfaces\ContextMenuStrategy::class,
            Strategies\DialogStrategy::class => Interfaces\DialogStrategy::class,
            Strategies\DockStrategy::class => Interfaces\DockStrategy::class,
            Strategies\GlobalShortcutStrategy::class => Interfaces\GlobalShortcutStrategy::class,
            Strategies\MenuStrategy::class => Interfaces\MenuStrategy::class,
            Strategies\MenuBarStrategy::class => Interfaces\MenuBarStrategy::class,
            Strategies\NotificationStrategy::class => Interfaces\NotificationStrategy::class,
            Strategies\PowerMonitorStrategy::class => Interfaces\PowerMonitorStrategy::class,
            Strategies\ProcessStrategy::class => Interfaces\ProcessStrategy::class,
            Strategies\ProgressBarStrategy::class => Interfaces\ProgressBarStrategy::class,
            Strategies\ScreenStrategy::class => Interfaces\ScreenStrategy::class,
            Strategies\SettingsStrategy::class => Interfaces\SettingsStrategy::class,
            Strategies\ShellStrategy::class => Interfaces\ShellStrategy::class,
            Strategies\SystemStrategy::class => Interfaces\SystemStrategy::class,
            Strategies\WindowStrategy::class => Interfaces\WindowStrategy::class,
        ];
    }

    public function getControllers(): array
    {
        return [
            BootedController::class,
            EventsController::class,
            CookieController::class,
        ];
    }

    public function getCommands(): array
    {
        return [
            ConfigCommand::class,
            PhpIniCommand::class,
            ServeCommand::class,
        ];
    }

    public function getListeners(): array
    {
        return [
            AppBooted::class => [OpenWindowsOnBoot::class],
        ];
    }

    public function load(): void
    {
        if (! $this->container instanceof HasBindings) {
            return;
        }

        $this->container->bindFactory(
            ClientConfig::class,
            fn () => ClientConfig::fromEnvironment()
        );
    }
}
