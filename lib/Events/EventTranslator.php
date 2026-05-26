<?php

namespace PHPNomad\NativePHP\Integration\Events;

/**
 * Maps Electron's outgoing event names (Laravel-style class strings like
 * "\\Native\\Laravel\\Events\\Windows\\WindowFocused") to PHPNomad event
 * classes. Anything unknown gets wrapped in UnknownNativeEvent so handlers
 * can opt into the long tail without needing typed classes.
 */
class EventTranslator
{
    /**
     * @var array<string, class-string<NativeEvent>>
     */
    private array $map = [
        'WindowFocused' => WindowFocused::class,
        'WindowBlurred' => WindowBlurred::class,
        'WindowMinimized' => WindowMinimized::class,
        'WindowMaximized' => WindowMaximized::class,
        'WindowShown' => WindowShown::class,
        'WindowClosed' => WindowClosed::class,
        'WindowResized' => WindowResized::class,
        'OpenedFromURL' => AppOpenedFromUrl::class,
        'NotificationClicked' => NotificationClicked::class,
    ];

    /**
     * @param array<int|string, mixed> $payload
     */
    public function translate(string $sourceEventName, array $payload = []): NativeEvent
    {
        foreach ($this->map as $suffix => $class) {
            if (str_ends_with($sourceEventName, $suffix)) {
                return new $class($sourceEventName, $payload);
            }
        }

        return new UnknownNativeEvent($sourceEventName, $payload);
    }
}
