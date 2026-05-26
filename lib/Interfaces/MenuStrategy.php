<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface MenuStrategy
{
    /**
     * @param array<string, mixed> $item
     */
    public function add(array $item): self;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function items(array $items): self;

    public function setAsApplicationMenu(): void;
}
