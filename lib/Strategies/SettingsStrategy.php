<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\SettingsStrategy as SettingsStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class SettingsStrategy implements SettingsStrategyContract
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function get(string $key): mixed
    {
        return $this->client->get('settings/' . rawurlencode($key))['value'] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->client->post('settings/' . rawurlencode($key), ['value' => $value]);
    }

    public function forget(string $key): void
    {
        $this->client->delete('settings/' . rawurlencode($key));
    }
}
