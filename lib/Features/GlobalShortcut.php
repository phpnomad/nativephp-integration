<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class GlobalShortcut
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function register(string $key, string $event): void
    {
        $this->client->post('global-shortcuts', ['key' => $key, 'event' => $event]);
    }

    public function unregister(string $key): void
    {
        $this->client->delete('global-shortcuts', ['key' => $key]);
    }

    public function isRegistered(string $key): bool
    {
        return (bool) ($this->client->get('global-shortcuts/' . rawurlencode($key))['result'] ?? false);
    }
}
