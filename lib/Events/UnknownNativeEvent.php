<?php

namespace PHPNomad\NativePHP\Integration\Events;

/**
 * Concrete catch-all for incoming Electron events that don't map to a
 * typed PHPNomad event. Subscribe to this to handle events that haven't
 * been promoted to first-class classes yet.
 */
class UnknownNativeEvent extends NativeEvent
{
    public static function getId(): string
    {
        return 'phpnomad.nativephp.unknown_event';
    }
}
