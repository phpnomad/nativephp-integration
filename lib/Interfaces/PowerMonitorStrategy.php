<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface PowerMonitorStrategy
{
    public function systemIdleState(int $thresholdSeconds): string;

    public function systemIdleTime(): int;

    public function currentThermalState(): string;

    public function isOnBatteryPower(): bool;
}
