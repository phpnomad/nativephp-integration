<?php

namespace PHPNomad\NativePHP\Integration\Listeners;

use PHPNomad\Events\Interfaces\CanHandle;
use PHPNomad\Events\Interfaces\Event;
use PHPNomad\NativePHP\Integration\Events\AppBooted;
use PHPNomad\NativePHP\Integration\WindowManager;

/**
 * @implements CanHandle<AppBooted>
 */
class OpenWindowsOnBoot implements CanHandle
{
    public function __construct(private readonly WindowManager $windows)
    {
    }

    public function handle(Event $event): void
    {
        $this->windows->openAllOnBoot();
    }
}
