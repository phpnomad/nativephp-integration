<?php

namespace PHPNomad\NativePHP\Integration\Tests\Support;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

/**
 * In-process replacement for the HTTP client. Records every call so tests
 * can assert wire format without spinning up a real server.
 */
class SpyClient implements NativeClient
{
    /** @var array<int, array{method: string, endpoint: string, payload: array<string, mixed>}> */
    public array $calls = [];

    /** @var array<string, array<string, mixed>> */
    public array $responses = [];

    public function post(string $endpoint, array $payload = []): array
    {
        $this->calls[] = ['method' => 'POST', 'endpoint' => $endpoint, 'payload' => $payload];
        return $this->responses[$endpoint] ?? [];
    }

    public function get(string $endpoint, array $query = []): array
    {
        $this->calls[] = ['method' => 'GET', 'endpoint' => $endpoint, 'payload' => $query];
        return $this->responses[$endpoint] ?? [];
    }

    public function delete(string $endpoint, array $payload = []): array
    {
        $this->calls[] = ['method' => 'DELETE', 'endpoint' => $endpoint, 'payload' => $payload];
        return $this->responses[$endpoint] ?? [];
    }

    public function stub(string $endpoint, array $response): void
    {
        $this->responses[$endpoint] = $response;
    }

    public function lastCall(): ?array
    {
        return end($this->calls) ?: null;
    }
}
