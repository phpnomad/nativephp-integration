<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class Dock
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function setMenu(array $items): void
    {
        $this->client->post('dock', ['items' => $items]);
    }
}
