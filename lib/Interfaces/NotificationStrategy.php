<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface NotificationStrategy
{
    public function title(string $title): self;

    public function body(string $body): self;

    public function subtitle(string $subtitle): self;

    public function silent(bool $silent = true): self;

    public function icon(string $iconPath): self;

    public function sound(string $sound): self;

    public function show(): void;
}
