<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\MenuStrategy as MenuStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class MenuStrategy implements MenuStrategyContract
{
    /** @var array<array<string, mixed>> */
    private array $items = [];

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function add(array $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function items(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function setAsApplicationMenu(): void
    {
        $this->client->post('menu', ['items' => $this->items]);
    }
}
