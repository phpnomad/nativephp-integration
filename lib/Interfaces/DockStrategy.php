<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface DockStrategy
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function setMenu(array $items): void;
}
