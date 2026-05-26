<?php

namespace PHPNomad\NativePHP\Integration\Commands;

use PHPNomad\Config\Interfaces\ConfigStrategy;
use PHPNomad\Console\Interfaces\Command;
use PHPNomad\Console\Interfaces\Input;

/**
 * Emits the PHP ini overrides as JSON. Invoked by the Electron bootstrap
 * to seed the long-running PHP server with app-specific ini settings.
 *
 * App overrides live under `nativephp.php_ini`. A sane default is included.
 */
class PhpIniCommand implements Command
{
    public function __construct(private readonly ConfigStrategy $config)
    {
    }

    public function getSignature(): string
    {
        return 'native:php-ini';
    }

    public function getDescription(): string
    {
        return 'Emit php.ini overrides as JSON.';
    }

    public function handle(Input $input): int
    {
        $defaults = [
            'memory_limit' => '512M',
        ];

        $overrides = (array) $this->config->get('nativephp.php_ini', []);
        echo json_encode(array_merge($defaults, $overrides), JSON_UNESCAPED_SLASHES);
        return 0;
    }
}
