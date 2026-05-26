<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit;

use PHPNomad\Di\Container\Container;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;
use PHPNomad\NativePHP\Integration\DataObjects\ClientConfig;
use PHPNomad\NativePHP\Integration\Initializer;
use PHPNomad\NativePHP\Integration\Interfaces;
use PHPNomad\NativePHP\Integration\Strategies;
use PHPNomad\NativePHP\Integration\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Confirms the Initializer binds every Feature strategy through its
 * interface so consumers can resolve and/or rebind any of them.
 *
 * If a new Feature is added, register its concrete → interface mapping
 * in Initializer::getClassDefinitions and add a row to $strategies
 * below. The test will fail until both ends are wired.
 */
class InitializerBindingsTest extends TestCase
{
    /**
     * @return array<int, array{0: class-string, 1: class-string}>
     */
    public static function strategies(): array
    {
        return [
            [Interfaces\AlertStrategy::class, Strategies\AlertStrategy::class],
            [Interfaces\ClipboardStrategy::class, Strategies\ClipboardStrategy::class],
            [Interfaces\ContextMenuStrategy::class, Strategies\ContextMenuStrategy::class],
            [Interfaces\DialogStrategy::class, Strategies\DialogStrategy::class],
            [Interfaces\DockStrategy::class, Strategies\DockStrategy::class],
            [Interfaces\GlobalShortcutStrategy::class, Strategies\GlobalShortcutStrategy::class],
            [Interfaces\MenuStrategy::class, Strategies\MenuStrategy::class],
            [Interfaces\MenuBarStrategy::class, Strategies\MenuBarStrategy::class],
            [Interfaces\NotificationStrategy::class, Strategies\NotificationStrategy::class],
            [Interfaces\PowerMonitorStrategy::class, Strategies\PowerMonitorStrategy::class],
            [Interfaces\ProcessStrategy::class, Strategies\ProcessStrategy::class],
            [Interfaces\ProgressBarStrategy::class, Strategies\ProgressBarStrategy::class],
            [Interfaces\ScreenStrategy::class, Strategies\ScreenStrategy::class],
            [Interfaces\SettingsStrategy::class, Strategies\SettingsStrategy::class],
            [Interfaces\ShellStrategy::class, Strategies\ShellStrategy::class],
            [Interfaces\SystemStrategy::class, Strategies\SystemStrategy::class],
            [Interfaces\WindowStrategy::class, Strategies\WindowStrategy::class],
        ];
    }

    #[DataProvider('strategies')]
    public function test_strategy_resolves_from_container_via_interface(
        string $interface,
        string $concrete
    ): void {
        $container = $this->bootContainer();
        $resolved = $container->get($interface);

        $this->assertInstanceOf($interface, $resolved);
        $this->assertInstanceOf($concrete, $resolved);
    }

    public function test_native_client_resolves_via_interface(): void
    {
        $container = $this->bootContainer();
        $client = $container->get(NativeClient::class);
        $this->assertInstanceOf(NativeClient::class, $client);
    }

    /**
     * Apply the Initializer's class definitions and config-factory binding
     * to a fresh container without running the full Bootstrapper. We don't
     * want the test to depend on RestStrategy / EventStrategy / etc. — that
     * coupling belongs in an end-to-end Kernel test, not a binding sanity
     * check.
     */
    private function bootContainer(): Container
    {
        $container = new Container();
        $initializer = new Initializer();

        foreach ($initializer->getClassDefinitions() as $concrete => $abstracts) {
            $abstracts = is_array($abstracts) ? $abstracts : [$abstracts];
            $container->bind($concrete, ...$abstracts);
        }

        $container->bindFactory(
            ClientConfig::class,
            fn () => ClientConfig::fromEnvironment()
        );

        return $container;
    }
}
