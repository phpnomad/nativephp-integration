<?php

namespace PHPNomad\NativePHP\Integration\Http;

use PHPNomad\Http\Interfaces\Request;
use PHPNomad\NativePHP\Integration\DataObjects\ClientConfig;
use PHPNomad\Rest\Exceptions\RestException;
use PHPNomad\Rest\Interfaces\Middleware;

/**
 * Verifies the X-NativePHP-Secret header on incoming Electron callbacks.
 * Without it, anything in the host browser could hit /_native/api/events.
 */
class SecretGuard implements Middleware
{
    public function __construct(private readonly ClientConfig $config)
    {
    }

    public function process(Request $request): void
    {
        $headers = $request->getHeaders();
        $provided = '';
        foreach ($headers as $name => $value) {
            if (strcasecmp($name, 'X-NativePHP-Secret') === 0) {
                $provided = (string) $value;
                break;
            }
        }

        if ($provided === '' || ! hash_equals($this->config->secret, $provided)) {
            throw new RestException('Forbidden', [], 403);
        }
    }
}
