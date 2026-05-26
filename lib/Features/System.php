<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class System
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function canPromptTouchId(): bool
    {
        return (bool) ($this->client->get('system/can-prompt-touch-id')['result'] ?? false);
    }

    public function promptTouchId(string $reason): bool
    {
        return (bool) ($this->client->post('system/prompt-touch-id', ['reason' => $reason])['result'] ?? false);
    }

    public function canEncrypt(): bool
    {
        return (bool) ($this->client->get('system/can-encrypt')['result'] ?? false);
    }

    public function encrypt(string $value): string
    {
        return $this->client->post('system/encrypt', ['value' => $value])['result'] ?? '';
    }

    public function decrypt(string $value): string
    {
        return $this->client->post('system/decrypt', ['value' => $value])['result'] ?? '';
    }

    public function printers(): array
    {
        return $this->client->get('system/printers')['printers'] ?? [];
    }

    public function theme(?string $value = null): ?string
    {
        if ($value === null) {
            return $this->client->get('system/theme')['result'] ?? null;
        }

        return $this->client->post('system/theme', ['theme' => $value])['result'] ?? null;
    }
}
