<?php
/**
 * Sandbox driver: wires the integration through a real PHPNomad DI container
 * and Bootstrapper, then fires a notification and opens a window. Run against
 * fake-electron-server.js to inspect the wire format.
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PHPNomad\Di\Container\Container;
use PHPNomad\Loader\Bootstrapper;
use PHPNomad\NativePHP\Integration\Features\Notification;
use PHPNomad\NativePHP\Integration\Features\Window;
use PHPNomad\NativePHP\Integration\Initializer;

$container = new Container();
$bootstrapper = new Bootstrapper($container, new Initializer());
$bootstrapper->load();

$notification = $container->get(Notification::class);
$notification
    ->title('Hello from PHPNomad')
    ->body('Routed through the NativePHP integration.')
    ->subtitle('experiment')
    ->show();

echo "notification dispatched\n";

$window = $container->get(Window::class);
$window
    ->url('https://example.com')
    ->title('PHPNomad window')
    ->size(1024, 768)
    ->open();

echo "window opened\n";
