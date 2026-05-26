<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class Screen
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function displays(): array
    {
        return $this->client->get('screen/displays');
    }

    public function primaryDisplay(): array
    {
        return $this->client->get('screen/primary-display');
    }

    public function cursorPosition(): array
    {
        return $this->client->get('screen/cursor-position');
    }
}
