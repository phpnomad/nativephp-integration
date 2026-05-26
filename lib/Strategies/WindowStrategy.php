<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\WindowStrategy as WindowStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class WindowStrategy implements WindowStrategyContract
{
    private string $id = 'main';
    private string $url = '';
    private string $title = '';
    private int $width = 800;
    private int $height = 600;
    private ?int $x = null;
    private ?int $y = null;
    private bool $frame = true;
    private bool $resizable = true;
    private bool $alwaysOnTop = false;

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function id(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function size(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function position(int $x, int $y): self
    {
        $this->x = $x;
        $this->y = $y;
        return $this;
    }

    public function frameless(bool $frameless = true): self
    {
        $this->frame = ! $frameless;
        return $this;
    }

    public function resizable(bool $resizable = true): self
    {
        $this->resizable = $resizable;
        return $this;
    }

    public function alwaysOnTop(bool $alwaysOnTop = true): self
    {
        $this->alwaysOnTop = $alwaysOnTop;
        return $this;
    }

    public function open(): void
    {
        $this->client->post('window/open', array_filter([
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'width' => $this->width,
            'height' => $this->height,
            'x' => $this->x,
            'y' => $this->y,
            'frame' => $this->frame,
            'resizable' => $this->resizable,
            'alwaysOnTop' => $this->alwaysOnTop,
        ], fn ($v) => $v !== null && $v !== ''));
    }

    public function close(): void
    {
        $this->client->post('window/close', ['id' => $this->id]);
    }

    public function minimize(): void
    {
        $this->client->post('window/minimize', ['id' => $this->id]);
    }

    public function maximize(): void
    {
        $this->client->post('window/maximize', ['id' => $this->id]);
    }

    public function hide(): void
    {
        $this->client->post('window/hide', ['id' => $this->id]);
    }

    public function resize(int $width, int $height): void
    {
        $this->client->post('window/resize', [
            'id' => $this->id,
            'width' => $width,
            'height' => $height,
        ]);
    }
}
