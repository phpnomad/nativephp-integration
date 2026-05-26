<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface SettingsStrategy
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    public function forget(string $key): void;
}
