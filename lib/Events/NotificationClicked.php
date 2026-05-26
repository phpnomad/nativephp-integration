<?php

namespace PHPNomad\NativePHP\Integration\Events;

class NotificationClicked extends NativeEvent
{
    public static function getId(): string
    {
        return 'phpnomad.nativephp.notification_clicked';
    }
}
