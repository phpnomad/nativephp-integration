<?php

namespace SandboxApp\Listeners;

use PHPNomad\Events\Interfaces\CanHandle;
use PHPNomad\Events\Interfaces\Event;
use PHPNomad\NativePHP\Integration\Events\NativeEvent;

/**
 * @implements CanHandle<Event>
 */
class LogEvents implements CanHandle
{
    public function handle(Event $event): void
    {
        $line = static::class . ' got ' . get_class($event);
        if ($event instanceof NativeEvent) {
            $line .= ' (' . $event->sourceEventName . ' ' . json_encode($event->payload) . ')';
        }

        $path = (getenv('NATIVEPHP_STORAGE_PATH') ?: sys_get_temp_dir()) . '/phpnomad-events.log';
        @file_put_contents($path, '[' . date('c') . '] ' . $line . "\n", FILE_APPEND);
    }
}
