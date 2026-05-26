<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Strategies;

use PHPNomad\NativePHP\Integration\Strategies\ShellStrategy;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class ShellStrategyTest extends TestCase
{
    public function test_open_external_url(): void
    {
        $client = new SpyClient();
        (new ShellStrategy($client))->openExternal('https://example.com');
        $this->assertSame('shell/open-external', $client->calls[0]['endpoint']);
        $this->assertSame(['url' => 'https://example.com'], $client->calls[0]['payload']);
    }

    public function test_trash_item_uses_delete(): void
    {
        $client = new SpyClient();
        (new ShellStrategy($client))->trashItem('/tmp/foo');
        $this->assertSame('DELETE', $client->calls[0]['method']);
        $this->assertSame('shell/trash-item', $client->calls[0]['endpoint']);
        $this->assertSame(['path' => '/tmp/foo'], $client->calls[0]['payload']);
    }
}
