<?php

namespace PHPNomad\NativePHP\Integration\Commands;

use PHPNomad\Config\Interfaces\ConfigStrategy;
use PHPNomad\Console\Interfaces\Command;
use PHPNomad\Console\Interfaces\Input;

/**
 * Emits the NativePHP startup configuration as JSON on stdout. Invoked by
 * the (patched) Electron bootstrap to learn the app id, deeplink scheme,
 * window definitions, updater settings, etc.
 *
 * App config lives under the `nativephp` key. See README for the schema.
 */
class ConfigCommand implements Command
{
    public function __construct(private readonly ConfigStrategy $config)
    {
    }

    public function getSignature(): string
    {
        return 'native:config';
    }

    public function getDescription(): string
    {
        return 'Emit the NativePHP startup configuration as JSON.';
    }

    public function handle(Input $input): int
    {
        $payload = (array) $this->config->get('nativephp', []);
        echo json_encode($payload, JSON_UNESCAPED_SLASHES);
        return 0;
    }
}
