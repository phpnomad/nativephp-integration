<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Features;

use PHPNomad\NativePHP\Integration\Features\Clipboard;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class ClipboardTest extends TestCase
{
    public function test_text_round_trip(): void
    {
        $client = new SpyClient();
        $client->stub('clipboard/text', ['text' => 'hello']);

        $clipboard = new Clipboard($client);
        $this->assertSame('hello', $clipboard->text());

        $clipboard->writeText('world');
        $write = $client->calls[1];
        $this->assertSame('POST', $write['method']);
        $this->assertSame('clipboard/text', $write['endpoint']);
        $this->assertSame(['text' => 'world'], $write['payload']);
    }

    public function test_clear(): void
    {
        $client = new SpyClient();
        (new Clipboard($client))->clear();
        $this->assertSame('DELETE', $client->calls[0]['method']);
        $this->assertSame('clipboard', $client->calls[0]['endpoint']);
    }
}
