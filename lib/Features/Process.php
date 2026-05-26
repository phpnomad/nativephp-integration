<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class Process
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        $response = $this->client->get('process');
        return $response['processes'] ?? [];
    }
}
