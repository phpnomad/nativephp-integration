<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit;

use PHPNomad\NativePHP\Integration\DataObjects\WindowDefinition;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\WindowManager;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class WindowManagerTest extends TestCase
{
    public function test_open_uses_registered_definition(): void
    {
        $client = new SpyClient();
        $mgr = new WindowManager($client);
        $mgr->register(new WindowDefinition(
            id: 'main',
            url: 'http://127.0.0.1:8100/',
            title: 'Main',
            width: 900,
            height: 700,
        ));

        $mgr->open('main');

        $payload = $client->calls[0]['payload'];
        $this->assertSame('main', $payload['id']);
        $this->assertSame('http://127.0.0.1:8100/', $payload['url']);
        $this->assertSame(900, $payload['width']);
        $this->assertSame(700, $payload['height']);
    }

    public function test_open_all_on_boot_skips_definitions_marked_otherwise(): void
    {
        $client = new SpyClient();
        $mgr = new WindowManager($client);
        $mgr->register(new WindowDefinition('main', 'http://x/', openOnBoot: true));
        $mgr->register(new WindowDefinition('settings', 'http://x/settings', openOnBoot: false));

        $mgr->openAllOnBoot();

        $opened = array_filter(
            $client->calls,
            fn ($c) => $c['endpoint'] === 'window/open'
        );
        $this->assertCount(1, $opened);
        $this->assertSame('main', array_values($opened)[0]['payload']['id']);
    }

    public function test_open_unknown_id_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new WindowManager(new SpyClient()))->open('nope');
    }
}
