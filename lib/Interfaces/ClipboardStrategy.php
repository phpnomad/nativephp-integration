<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ClipboardStrategy
{
    public function text(): ?string;

    public function writeText(string $text): void;

    public function html(): ?string;

    public function writeHtml(string $html): void;

    public function image(): ?string;

    public function writeImage(string $base64DataUri): void;

    public function clear(): void;
}
