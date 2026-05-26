<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\ShellStrategy as ShellStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class ShellStrategy implements ShellStrategyContract
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function showItemInFolder(string $path): void
    {
        $this->client->post('shell/show-item-in-folder', ['path' => $path]);
    }

    public function openItem(string $path): string
    {
        return $this->client->post('shell/open-item', ['path' => $path])['result'] ?? '';
    }

    public function openExternal(string $url): void
    {
        $this->client->post('shell/open-external', ['url' => $url]);
    }

    public function trashItem(string $path): void
    {
        $this->client->delete('shell/trash-item', ['path' => $path]);
    }
}
