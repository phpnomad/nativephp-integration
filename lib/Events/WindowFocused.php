<?php

namespace PHPNomad\NativePHP\Integration\Events;

class WindowFocused extends NativeEvent
{
    public function windowId(): string
    {
        return (string) ($this->payload[0] ?? 'main');
    }

    public static function getId(): string
    {
        return 'phpnomad.nativephp.window_focused';
    }
}
