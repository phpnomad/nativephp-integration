<?php

namespace PHPNomad\NativePHP\Integration\DataObjects;

class ClientConfig
{
    public function __construct(
        public readonly string $apiUrl,
        public readonly string $secret,
        public readonly float $timeoutSeconds = 60.0,
    ) {
    }

    public static function fromEnvironment(): self
    {
        $apiUrl = getenv('NATIVEPHP_API_URL') ?: '';
        $secret = getenv('NATIVEPHP_SECRET') ?: '';

        return new self($apiUrl, $secret);
    }

    public function isConfigured(): bool
    {
        return $this->apiUrl !== '' && $this->secret !== '';
    }
}
