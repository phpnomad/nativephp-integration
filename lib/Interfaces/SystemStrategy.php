<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface SystemStrategy
{
    public function canPromptTouchId(): bool;

    public function promptTouchId(string $reason): bool;

    public function canEncrypt(): bool;

    public function encrypt(string $value): string;

    public function decrypt(string $value): string;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function printers(): array;

    public function theme(?string $value = null): ?string;
}
