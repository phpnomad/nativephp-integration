<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\ClipboardStrategy as ClipboardStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class ClipboardStrategy implements ClipboardStrategyContract
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function text(): ?string
    {
        return $this->client->get('clipboard/text')['text'] ?? null;
    }

    public function writeText(string $text): void
    {
        $this->client->post('clipboard/text', ['text' => $text]);
    }

    public function html(): ?string
    {
        return $this->client->get('clipboard/html')['html'] ?? null;
    }

    public function writeHtml(string $html): void
    {
        $this->client->post('clipboard/html', ['html' => $html]);
    }

    public function image(): ?string
    {
        return $this->client->get('clipboard/image')['image'] ?? null;
    }

    public function writeImage(string $base64DataUri): void
    {
        $this->client->post('clipboard/image', ['image' => $base64DataUri]);
    }

    public function clear(): void
    {
        $this->client->delete('clipboard');
    }
}
