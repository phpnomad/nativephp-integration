# phpnomad/nativephp-integration

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/nativephp-integration.svg)](https://packagist.org/packages/phpnomad/nativephp-integration)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/nativephp-integration.svg)](https://packagist.org/packages/phpnomad/nativephp-integration)
[![License](https://img.shields.io/packagist/l/phpnomad/nativephp-integration.svg)](https://packagist.org/packages/phpnomad/nativephp-integration)

PHPNomad host adapter for [NativePHP](https://nativephp.com). Lets a PHPNomad app run as the PHP guest inside an Electron desktop process, without Laravel.

Sits at the same architectural level as `phpnomad/wordpress-integration` — it binds NativePHP's runtime primitives (windows, dialogs, notifications, system access) to PHPNomad's strategy interfaces, so apps slot in as PHPNomad Initializers and never touch the Electron HTTP API directly.

## Status — v0.1, experimental preview

This package is **functionally complete and end-to-end verified against real Electron**. It is being released as `v0.1.x` rather than `v1.0.0` because of one outstanding upstream dependency:

The Electron-side `@nativephp/electron-plugin` is currently Laravel-coupled in three small places (`'artisan'` bin name, server.php router path, Laravel-specific bootstrap commands). A minimal three-env-var patch that opens those up for non-Laravel guests is awaiting review as [NativePHP/electron#265](https://github.com/NativePHP/electron/pull/265). Until that lands, consumers apply the same patch locally via `patch-package` (recipe below — about two extra lines in your `package.json`).

When PR #265 merges and ships in a NativePHP release, the patch step goes away and this package will tag `v1.0.0` without API changes.

## What This Provides

- **17 fluent feature classes** for the Electron API: `Notification`, `Window`, `Dialog`, `Clipboard`, `Shell`, `Settings`, `Screen`, `System`, `Dock`, `MenuBar`, `Menu`, `ContextMenu`, `PowerMonitor`, `GlobalShortcut`, `ProgressBar`, `Process`, `Alert`
- **Typed event classes** for Electron's outbound webhooks: `AppBooted`, `WindowFocused`, `WindowBlurred`, `WindowMinimized`, `WindowMaximized`, `WindowShown`, `WindowClosed`, `WindowResized`, `AppOpenedFromUrl`, `NotificationClicked`, `UnknownNativeEvent` (catch-all). Translated from the raw JS event names by `EventTranslator`.
- **REST controllers** for the inbound callback routes (`/_native/api/booted`, `/_native/api/events`, `/_native/api/cookie`), wired through `phpnomad/fastroute-rest-integration` with a secret-header `SecretGuard` middleware.
- **Symfony Console commands** (`native:config`, `native:php-ini`, `native:serve`) registered through `phpnomad/symfony-console-integration`. The Electron bootstrap invokes the first two to learn app metadata and PHP ini overrides.
- **Declarative `WindowManager`** — register `WindowDefinition` objects once; the integration opens them at boot via the `OpenWindowsOnBoot` listener.
- **HTTP `Client`** with the `X-NativePHP-Secret` header baked in, unified error type (`NativePHPException`), and PSR-7 compatible plumbing via Guzzle.

## Installation

```bash
composer require phpnomad/nativephp-integration
```

Then register `Initializer` in your bootstrapper alongside the strategy bindings it composes with:

```php
use PHPNomad\Loader\Bootstrapper;
use PHPNomad\Di\Container\Container;
use PHPNomad\NativePHP\Integration\Initializer as NativePHP;
use PHPNomad\Symfony\Component\EventDispatcherIntegration\Initializer as Events;
use PHPNomad\Symfony\Component\Console\Initializer as Console;
use PHPNomad\FastRoute\Component\RestInitializer as Rest;

$container = new Container();
(new Bootstrapper(
    $container,
    new YourBindings(),     // logger + CurrentContextResolverStrategy + User
    new YourConfig(),       // ConfigStrategy with `nativephp.*` keys
    new Events(),
    new Console(),
    new Rest(),
    new NativePHP(),
    new YourApp(),          // your window defs, listeners, etc.
))->load();
```

## Electron-side setup (the patch-package step)

The Electron plugin needs a small patch until [NativePHP/electron#265](https://github.com/NativePHP/electron/pull/265) merges. The patch is bundled at `sandbox/electron/patches/@nativephp+electron-plugin+0.5.5.patch` in this repo — copy it into your app's `electron-host/patches/` directory and wire up `patch-package` in `package.json`:

```json
{
  "scripts": {
    "postinstall": "patch-package"
  },
  "devDependencies": {
    "patch-package": "^8.0.1"
  }
}
```

`npm install` will then automatically apply the patch every time. The patch is idempotent and survives reinstalls.

Then set these env vars before requiring `@nativephp/electron-plugin` in your Electron main process:

```js
Object.assign(process.env, {
    NATIVEPHP_PHP_BOOT_BIN: '/abs/path/to/your/bin/nomad',
    NATIVEPHP_SERVER_SCRIPT: '/abs/path/to/your/public/index.php',
    NATIVEPHP_SERVER_CWD: '/abs/path/to/your/public',
    NATIVEPHP_SKIP_LARAVEL_SETUP: '1',
});

const nativePHP = require('@nativephp/electron-plugin');
nativePHP.bootstrap(app, icon, phpBinary, cert);
```

A complete working example lives in `sandbox/electron/main-full.js`.

## Using a Feature

```php
use PHPNomad\NativePHP\Integration\Features\Notification;

$container->get(Notification::class)
    ->title('Hello')
    ->body('From a PHPNomad app')
    ->show();
```

## Listening to a Native Event

```php
class MyHandler implements PHPNomad\Events\Interfaces\CanHandle
{
    public function handle(PHPNomad\Events\Interfaces\Event $event): void
    {
        // $event is a PHPNomad\NativePHP\Integration\Events\WindowFocused
        $windowId = $event->windowId();
    }
}

// In your app initializer:
public function getListeners(): array
{
    return [
        \PHPNomad\NativePHP\Integration\Events\WindowFocused::class => [MyHandler::class],
    ];
}
```

## Registering a Window

```php
use PHPNomad\NativePHP\Integration\WindowManager;
use PHPNomad\NativePHP\Integration\DataObjects\WindowDefinition;

$container->get(WindowManager::class)->register(new WindowDefinition(
    id: 'main',
    url: 'http://127.0.0.1:8100/',
    title: 'My App',
    width: 1024,
    height: 768,
));
```

The integration opens registered windows automatically when the `AppBooted` event fires.

## Architecture

```
┌─────────────────────────────────────────────────┐
│ Electron main process (TypeScript)              │
│ ├── @nativephp/electron-plugin (patched)        │
│ │   ├── /api/* HTTP server                      │
│ │   └── spawn(php bin/nomad ...)                │
│ └── BrowserWindow → loads http://localhost:N/   │
└────────────────┬────────────────────────────────┘
                 │ HTTP, X-NativePHP-Secret
┌────────────────▼────────────────────────────────┐
│ Your PHPNomad app (this integration)            │
│ ├── bin/nomad   (symfony-console-integration)   │
│ │   ├── native:config                           │
│ │   ├── native:php-ini                          │
│ │   └── native:serve                            │
│ ├── public/index.php (fastroute)                │
│ │   ├── /_native/api/booted   → BootedController│
│ │   ├── /_native/api/events   → EventsController│
│ │   └── /_native/api/cookie   → CookieController│
│ └── Your app code                               │
│     ├── Feature classes (Notification, etc.)    │
│     ├── Event listeners (AppBooted, ...)        │
│     └── WindowManager registrations             │
└─────────────────────────────────────────────────┘
```

The integration is the brace in the middle. Apps slot in as PHPNomad Initializers and never touch the Electron HTTP API directly.

## Testing

```bash
composer test
```

23 unit tests covering Feature wire format, event translation, the window manager, and the typed event API. Tests use a `SpyClient` so they run without a network or Electron — fast and deterministic.

## What's not done yet

- The upstream patch hasn't merged (PR #265 above). Once it does, the `patch-package` step goes away and this package tags `v1.0.0`.
- `phpnomad/symfony-console-integration` requires `dev-main` until a `1.0.5` release is tagged — the integration depends on a fix that's on `main` but not yet in a tagged release.
- No app-starter scaffold yet. The sandbox under `sandbox/` is the working reference; a proper `phpnomad/electron-app-starter` is a planned follow-up.
- No `nativephp/php-bin` wiring for portable PHP runtimes (needed for cross-platform distributable installers).
- No auto-updater event surface (`electron-updater` is in the plugin's deps but the integration doesn't expose its events as PHPNomad events yet).

## Running the sandbox

```bash
cd sandbox/electron
npm install
./node_modules/.bin/electron .
```

Electron boots, the plugin calls `bin/nomad native:config` to learn the app shape, spawns `php -S` pointed at `sandbox/app/public/index.php`, and opens a window on the served page. Click "Fire notification" in the window → a native toast appears. Focus the window → a different toast appears (the demo handler fires one back through the integration when it catches the `WindowFocused` event).

## License

MIT License. See [LICENSE.txt](LICENSE.txt).
