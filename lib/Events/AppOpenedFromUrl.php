<?php

namespace PHPNomad\NativePHP\Integration\Events;

class AppOpenedFromUrl extends NativeEvent
{
    public function url(): string
    {
        return (string) ($this->payload[0] ?? '');
    }

    public static function getId(): string
    {
        return 'phpnomad.nativephp.app_opened_from_url';
    }
}
