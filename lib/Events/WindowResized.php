<?php

namespace PHPNomad\NativePHP\Integration\Events;

class WindowResized extends NativeEvent
{
    public function windowId(): string
    {
        return (string) ($this->payload[0] ?? 'main');
    }

    public function width(): int
    {
        return (int) ($this->payload[1] ?? 0);
    }

    public function height(): int
    {
        return (int) ($this->payload[2] ?? 0);
    }

    public static function getId(): string
    {
        return 'phpnomad.nativephp.window_resized';
    }
}
