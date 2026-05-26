<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\ProcessStrategy as ProcessStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class ProcessStrategy implements ProcessStrategyContract
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
