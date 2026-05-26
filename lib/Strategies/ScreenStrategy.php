<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\ScreenStrategy as ScreenStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class ScreenStrategy implements ScreenStrategyContract
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
