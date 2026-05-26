<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ContextMenuStrategy
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function items(array $items): self;

    public function register(): void;

    public function remove(): void;
}
