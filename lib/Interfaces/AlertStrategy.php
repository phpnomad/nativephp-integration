<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface AlertStrategy
{
    public function title(string $title): self;

    public function type(string $type): self;

    /**
     * @param array<int, string> $buttons
     */
    public function buttons(array $buttons, int $defaultId = 0): self;

    public function detail(string $detail): self;

    /**
     * @return array<string, mixed>
     */
    public function show(string $message): array;

    /**
     * @return array<string, mixed>
     */
    public function error(string $message): array;
}
