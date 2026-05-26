<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\DockStrategy as DockStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class DockStrategy implements DockStrategyContract
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function setMenu(array $items): void
    {
        $this->client->post('dock', ['items' => $items]);
    }
}
