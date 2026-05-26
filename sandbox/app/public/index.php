<?php
/**
 * HTTP front controller — boots the Kernel, fires the RequestInitiated
 * event so fastroute can dispatch to the right Controller, and emits the
 * resulting Response.
 *
 * Routes the integration provides:
 *   POST /_native/api/booted    → BootedController
 *   POST /_native/api/events    → EventsController
 *   GET  /_native/api/cookie    → CookieController
 *
 * The page rendered at `/` is also handled here (below fastroute, as a
 * fallback) since the renderer's main view is part of the PoC.
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\FastRoute\Component\Events\RequestInitiated;
use PHPNomad\FastRoute\Component\Response as RestResponse;
use PHPNomad\Http\Interfaces\Response;
use PHPNomad\NativePHP\Integration\Features\Notification;
use SandboxApp\Kernel;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// The built-in PHP server uses the router script for ALL requests; it
// only falls back to static files if we explicitly return false. Pass
// existing static files through (favicon etc.).
if ($method === 'GET' && $path !== '/' && file_exists(__DIR__ . $path)) {
    return false;
}

$container = Kernel::boot();

// Routes registered with fastroute (the integration's controllers).
$natoveRoutes = ['/_native/api/booted', '/_native/api/events', '/_native/api/cookie'];
$isNativeRoute = in_array($path, $natoveRoutes, true);

if ($isNativeRoute) {
    try {
        $event = new RequestInitiated($method, $path);
        $container->get(EventStrategy::class)->broadcast($event);
        sendResponse($event->getResponse());
    } catch (\PHPNomad\Rest\Exceptions\RestException $e) {
        http_response_code($e->getCode() ?: 400);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
    return;
}

// Demo endpoint: lets the renderer JS fire a notification through PHP.
if ($method === 'POST' && $path === '/api/fire-notification') {
    $container->get(Notification::class)
        ->title('From the running PHPNomad app')
        ->body('Posted by clicking inside the BrowserWindow.')
        ->show();

    http_response_code(204);
    return;
}

// Health endpoint for the sandbox smoke tests.
if ($method === 'GET' && $path === '/health') {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'pid' => getmypid()]);
    return;
}

// Default: render the main window page.
header('Content-Type: text/html; charset=utf-8');
$apiUrl = htmlspecialchars(getenv('NATIVEPHP_API_URL') ?: '', ENT_QUOTES);
$secret = (string) (getenv('NATIVEPHP_SECRET') ?: '');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHPNomad × NativePHP</title>
    <style>
        body { font-family: system-ui, sans-serif; padding: 2.5rem; max-width: 720px; margin: 0 auto; background: #0f172a; color: #e2e8f0; }
        h1 { font-size: 1.6rem; }
        .card { background: #1e293b; padding: 1.25rem 1.5rem; border-radius: 8px; margin-top: 1rem; }
        code { background: #334155; padding: 0.15rem 0.35rem; border-radius: 4px; }
        button { background: #6366f1; color: white; border: 0; padding: 0.6rem 1rem; border-radius: 6px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #4f46e5; }
        .log { margin-top: 0.6rem; font-family: ui-monospace, monospace; font-size: 0.85rem; color: #94a3b8; }
    </style>
</head>
<body>
    <h1>👋 PHPNomad is running inside Electron</h1>
    <div class="card">
        <strong>Process:</strong> PHP <?= PHP_VERSION ?> · pid <?= getmypid() ?><br>
        <strong>Electron API:</strong> <code><?= $apiUrl ?></code><br>
        <strong>Secret set:</strong> <?= $secret !== '' ? 'yes' : 'no' ?>
    </div>
    <div class="card">
        <p>Click below to fire a notification back through the integration.</p>
        <button id="fire">Fire notification</button>
        <div class="log" id="log"></div>
    </div>
    <script>
        document.getElementById('fire').addEventListener('click', async () => {
            const r = await fetch('/api/fire-notification', { method: 'POST' });
            document.getElementById('log').textContent = 'POST /api/fire-notification → ' + r.status + ' at ' + new Date().toLocaleTimeString();
        });
    </script>
</body>
</html>
<?php

function sendResponse(Response $response): void
{
    http_response_code($response->getStatus());
    foreach ($response->getHeaders() as $name => $value) {
        header("{$name}: {$value}");
    }
    echo $response->getBody();
}
