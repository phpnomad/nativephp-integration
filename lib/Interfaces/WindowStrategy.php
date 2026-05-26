<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface WindowStrategy
{
    public function id(string $id): self;

    public function url(string $url): self;

    public function title(string $title): self;

    public function size(int $width, int $height): self;

    public function position(int $x, int $y): self;

    public function frameless(bool $frameless = true): self;

    public function resizable(bool $resizable = true): self;

    public function alwaysOnTop(bool $alwaysOnTop = true): self;

    public function open(): void;

    public function close(): void;

    public function minimize(): void;

    public function maximize(): void;

    public function hide(): void;

    public function resize(int $width, int $height): void;
}
