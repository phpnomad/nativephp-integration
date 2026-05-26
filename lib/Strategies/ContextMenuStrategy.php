<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\ContextMenuStrategy as ContextMenuStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class ContextMenuStrategy implements ContextMenuStrategyContract
{
    /** @var array<array<string, mixed>> */
    private array $items = [];

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function items(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function register(): void
    {
        $this->client->post('context', ['items' => $this->items]);
    }

    public function remove(): void
    {
        $this->client->delete('context');
    }
}
