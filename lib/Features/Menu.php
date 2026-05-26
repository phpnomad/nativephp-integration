<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class Menu
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
