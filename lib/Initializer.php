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
use PHPNomad\NativePHP\Integration\Listeners\OpenWindowsOnBoot;
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
            Client::class => NativeClient::class,
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
