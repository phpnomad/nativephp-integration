<?php

namespace PHPNomad\NativePHP\Integration\Events;

use PHPNomad\Events\Interfaces\Event;

class AppBooted implements Event
{
    public static function getId(): string
    {
        return 'phpnomad.nativephp.app_booted';
    }
}
