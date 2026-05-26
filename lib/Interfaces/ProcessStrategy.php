<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ProcessStrategy
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array;
}
