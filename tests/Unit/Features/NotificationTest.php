<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Features;

use PHPNomad\NativePHP\Integration\Features\Notification;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_minimum_payload_only_includes_set_fields(): void
    {
        $client = new SpyClient();

        (new Notification($client))->title('Hello')->body('World')->show();

        $this->assertCount(1, $client->calls);
        $this->assertSame('POST', $client->calls[0]['method']);
        $this->assertSame('notification', $client->calls[0]['endpoint']);
        $this->assertSame(
            ['title' => 'Hello', 'body' => 'World', 'silent' => false],
            $client->calls[0]['payload']
        );
    }

    public function test_optional_fields_are_included_when_set(): void
    {
        $client = new SpyClient();

        (new Notification($client))
            ->title('Hi')
            ->body('Body')
            ->subtitle('sub')
            ->silent(true)
            ->icon('/path/to/icon.png')
            ->sound('ping')
            ->show();

        $payload = $client->calls[0]['payload'];

        $this->assertSame('Hi', $payload['title']);
        $this->assertSame('Body', $payload['body']);
        $this->assertSame('sub', $payload['subtitle']);
        $this->assertTrue($payload['silent']);
        $this->assertSame('/path/to/icon.png', $payload['icon']);
        $this->assertSame('ping', $payload['sound']);
    }
}
