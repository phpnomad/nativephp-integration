<?php

namespace PHPNomad\NativePHP\Integration\Events;

class WindowBlurred extends NativeEvent
{
    public function windowId(): string
    {
        return (string) ($this->payload[0] ?? 'main');
    }

    public static function getId(): string
    {
        return 'phpnomad.nativephp.window_blurred';
    }
}
