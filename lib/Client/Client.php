<?php

namespace PHPNomad\NativePHP\Integration\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;
use PHPNomad\NativePHP\Integration\DataObjects\ClientConfig;
use PHPNomad\NativePHP\Integration\Exceptions\NativePHPException;

class Client implements NativeClient
{
    private GuzzleClient $http;

    public function __construct(private readonly ClientConfig $config)
    {
        // Construct the underlying Guzzle client even when config is missing —
        // a CLI process that never calls Electron should still resolve
        // cleanly. Misconfiguration is reported per-request in request().
        $this->http = new GuzzleClient([
            'base_uri' => $this->config->apiUrl !== '' ? $this->config->apiUrl : 'http://invalid.local/',
            'timeout' => $this->config->timeoutSeconds,
            'headers' => [
                'X-NativePHP-Secret' => $this->config->secret,
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function post(string $endpoint, array $payload = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $payload]);
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    public function delete(string $endpoint, array $payload = []): array
    {
        return $this->request('DELETE', $endpoint, ['json' => $payload]);
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function request(string $method, string $endpoint, array $options): array
    {
        if (! $this->config->isConfigured()) {
            throw new NativePHPException(
                'NativePHP client is not configured. Expected NATIVEPHP_API_URL and NATIVEPHP_SECRET to be set.'
            );
        }

        try {
            $response = $this->http->request($method, ltrim($endpoint, '/'), $options);
        } catch (GuzzleException $e) {
            throw new NativePHPException(
                "NativePHP request failed [{$method} {$endpoint}]: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }

        $body = (string) $response->getBody();
        if ($body === '') {
            return [];
        }

        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : [];
    }
}
