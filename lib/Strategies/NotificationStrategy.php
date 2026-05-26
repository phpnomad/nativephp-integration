<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\NotificationStrategy as NotificationStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class NotificationStrategy implements NotificationStrategyContract
{
    private string $title = '';
    private string $body = '';
    private string $subtitle = '';
    private bool $silent = false;
    private ?string $icon = null;
    private ?string $sound = null;

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function body(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function subtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function silent(bool $silent = true): self
    {
        $this->silent = $silent;
        return $this;
    }

    public function icon(string $iconPath): self
    {
        $this->icon = $iconPath;
        return $this;
    }

    public function sound(string $sound): self
    {
        $this->sound = $sound;
        return $this;
    }

    public function show(): void
    {
        $this->client->post('notification', array_filter([
            'title' => $this->title,
            'body' => $this->body,
            'subtitle' => $this->subtitle,
            'silent' => $this->silent,
            'icon' => $this->icon,
            'sound' => $this->sound,
        ], fn ($v) => $v !== null && $v !== ''));
    }
}
