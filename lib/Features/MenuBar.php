<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class MenuBar
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function create(array $options): void
    {
        $this->client->post('menu-bar/create', $options);
    }

    public function label(string $label): void
    {
        $this->client->post('menu-bar/label', ['label' => $label]);
    }

    public function contextMenu(array $items): void
    {
        $this->client->post('menu-bar/context-menu', ['items' => $items]);
    }

    public function show(): void
    {
        $this->client->post('menu-bar/show');
    }

    public function hide(): void
    {
        $this->client->post('menu-bar/hide');
    }
}
