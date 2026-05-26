<?php

namespace PHPNomad\NativePHP\Integration\Contracts;

interface NativeClient
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function post(string $endpoint, array $payload = []): array;

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    public function get(string $endpoint, array $query = []): array;

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function delete(string $endpoint, array $payload = []): array;
}
