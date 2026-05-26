<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ProgressBarStrategy
{
    /**
     * Update the dock/taskbar progress bar.
     *
     * @param float $progress 0.0 to 1.0. Use -1 to clear.
     * @param string|null $mode 'normal' | 'indeterminate' | 'error' | 'paused' | 'none'
     */
    public function update(float $progress, ?string $mode = null): void;
}
