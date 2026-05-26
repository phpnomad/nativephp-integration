<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ScreenStrategy
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function displays(): array;

    /**
     * @return array<string, mixed>
     */
    public function primaryDisplay(): array;

    /**
     * @return array<string, mixed>
     */
    public function cursorPosition(): array;
}
