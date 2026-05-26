<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface MenuBarStrategy
{
    /**
     * @param array<string, mixed> $options
     */
    public function create(array $options): void;

    public function label(string $label): void;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function contextMenu(array $items): void;

    public function show(): void;

    public function hide(): void;
}
