<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface GlobalShortcutStrategy
{
    public function register(string $key, string $event): void;

    public function unregister(string $key): void;

    public function isRegistered(string $key): bool;
}
