<?php

namespace PHPNomad\NativePHP\Integration\Events;

use PHPNomad\Events\Interfaces\Event;

/**
 * Generic Electron-originated event. Carries the raw class name string
 * Electron sent (e.g. "\\Native\\Laravel\\Events\\Windows\\WindowFocused")
 * and its payload. Used for events that don't have a typed PHPNomad class.
 *
 * Concrete typed events (WindowFocused, WindowBlurred, etc.) extend this.
 */
abstract class NativeEvent implements Event
{
    /**
     * @param array<int|string, mixed> $payload
     */
    public function __construct(
        public readonly string $sourceEventName,
        public readonly array $payload = [],
    ) {
    }
}
