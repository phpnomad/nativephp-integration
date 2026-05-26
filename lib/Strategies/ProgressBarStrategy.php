<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\ProgressBarStrategy as ProgressBarStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class ProgressBarStrategy implements ProgressBarStrategyContract
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    /**
     * Update the dock/taskbar progress bar.
     *
     * @param float $progress 0.0 to 1.0. Use -1 to clear.
     * @param string|null $mode 'normal' | 'indeterminate' | 'error' | 'paused' | 'none'
     */
    public function update(float $progress, ?string $mode = null): void
    {
        $payload = ['progress' => $progress];
        if ($mode !== null) {
            $payload['mode'] = $mode;
        }

        $this->client->post('progress-bar/update', $payload);
    }
}
