<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Strategies;

use PHPNomad\NativePHP\Integration\Strategies\SystemStrategy;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class SystemStrategyTest extends TestCase
{
    public function test_can_encrypt_returns_bool(): void
    {
        $client = new SpyClient();
        $client->stub('system/can-encrypt', ['result' => true]);
        $this->assertTrue((new SystemStrategy($client))->canEncrypt());
    }

    public function test_theme_get_and_set(): void
    {
        $client = new SpyClient();
        $client->stub('system/theme', ['result' => 'dark']);

        $sys = new SystemStrategy($client);
        $this->assertSame('dark', $sys->theme());
        $this->assertSame('dark', $sys->theme('dark'));

        $this->assertSame('GET',  $client->calls[0]['method']);
        $this->assertSame('POST', $client->calls[1]['method']);
        $this->assertSame(['theme' => 'dark'], $client->calls[1]['payload']);
    }

    public function test_printers_list(): void
    {
        $client = new SpyClient();
        $client->stub('system/printers', ['printers' => [['name' => 'PDF']]]);
        $this->assertSame([['name' => 'PDF']], (new SystemStrategy($client))->printers());
    }
}
