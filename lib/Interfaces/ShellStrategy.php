<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ShellStrategy
{
    public function showItemInFolder(string $path): void;

    public function openItem(string $path): string;

    public function openExternal(string $url): void;

    public function trashItem(string $path): void;
}
