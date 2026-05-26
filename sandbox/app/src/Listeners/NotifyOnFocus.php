<?php

namespace SandboxApp\Listeners;

use PHPNomad\Events\Interfaces\CanHandle;
use PHPNomad\Events\Interfaces\Event;
use PHPNomad\NativePHP\Integration\Events\WindowFocused;
use PHPNomad\NativePHP\Integration\Strategies\NotificationStrategy;

/**
 * @implements CanHandle<WindowFocused>
 */
class NotifyOnFocus implements CanHandle
{
    public function __construct(private readonly Notification $notification)
    {
    }

    public function handle(Event $event): void
    {
        // The integration is healthy whether or not Electron is reachable;
        // a missing/dead API server shouldn't fail the inbound webhook.
        try {
            $this->notification
                ->title('PHPNomad caught a focus event')
                ->body('Electron told PHP the window was focused; PHP fired this back through the integration.')
                ->show();
        } catch (\Throwable $e) {
            // swallow — this is a side-effecting handler
        }
    }
}
