<?php

namespace PHPNomad\NativePHP\Integration\Commands;

use PHPNomad\Console\Interfaces\Command;
use PHPNomad\Console\Interfaces\Input;

/**
 * Starts the PHP built-in HTTP server pointed at the app's front controller.
 *
 * Mostly here for manual invocation (the Electron bootstrap spawns this
 * indirectly via `php -S` itself). Useful for `nomad serve` during dev.
 */
class ServeCommand implements Command
{
    public function getSignature(): string
    {
        return 'native:serve {--host=127.0.0.1:Bind host} {--port=8100:Bind port} {--script=:Path to the front controller}';
    }

    public function getDescription(): string
    {
        return 'Start the PHP HTTP server for local development.';
    }

    public function handle(Input $input): int
    {
        $host = (string) $input->getParam('host');
        $port = (string) $input->getParam('port');
        $script = (string) $input->getParam('script');

        if ($script === '') {
            fwrite(STDERR, "--script is required\n");
            return 1;
        }

        $cwd = dirname($script);
        $cmd = sprintf('php -S %s:%s %s', escapeshellarg($host), escapeshellarg($port), escapeshellarg($script));

        $descriptorspec = [STDIN, STDOUT, STDERR];
        $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);

        if (! is_resource($process)) {
            fwrite(STDERR, "failed to spawn php -S\n");
            return 1;
        }

        return proc_close($process);
    }
}
