<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\PowerMonitorStrategy as PowerMonitorStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class PowerMonitorStrategy implements PowerMonitorStrategyContract
{
    public function __construct(private readonly NativeClient $client)
    {
    }

    public function systemIdleState(int $thresholdSeconds): string
    {
        return $this->client->get('power-monitor/get-system-idle-state', ['threshold' => $thresholdSeconds])['result'] ?? '';
    }

    public function systemIdleTime(): int
    {
        return (int) ($this->client->get('power-monitor/get-system-idle-time')['result'] ?? 0);
    }

    public function currentThermalState(): string
    {
        return $this->client->get('power-monitor/get-current-thermal-state')['result'] ?? '';
    }

    public function isOnBatteryPower(): bool
    {
        return (bool) ($this->client->get('power-monitor/is-on-battery-power')['result'] ?? false);
    }
}
