<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Strategies;

use PHPNomad\NativePHP\Integration\Strategies\WindowStrategy;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class WindowStrategyTest extends TestCase
{
    public function test_open_sends_expected_payload(): void
    {
        $client = new SpyClient();

        (new WindowStrategy($client))
            ->id('main')
            ->url('https://example.com')
            ->title('Hi')
            ->size(1024, 768)
            ->position(10, 20)
            ->open();

        $call = $client->calls[0];
        $this->assertSame('POST', $call['method']);
        $this->assertSame('window/open', $call['endpoint']);
        $this->assertSame('main', $call['payload']['id']);
        $this->assertSame('https://example.com', $call['payload']['url']);
        $this->assertSame(1024, $call['payload']['width']);
        $this->assertSame(768, $call['payload']['height']);
        $this->assertSame(10, $call['payload']['x']);
        $this->assertSame(20, $call['payload']['y']);
    }

    public function test_lifecycle_methods_target_the_window_by_id(): void
    {
        $client = new SpyClient();

        $window = (new WindowStrategy($client))->id('settings');
        $window->minimize();
        $window->maximize();
        $window->hide();
        $window->close();

        $endpoints = array_map(fn ($c) => $c['endpoint'], $client->calls);
        $this->assertSame(
            ['window/minimize', 'window/maximize', 'window/hide', 'window/close'],
            $endpoints
        );
        foreach ($client->calls as $call) {
            $this->assertSame(['id' => 'settings'], $call['payload']);
        }
    }

    public function test_resize_sends_new_dimensions(): void
    {
        $client = new SpyClient();

        (new WindowStrategy($client))->id('main')->resize(640, 480);

        $this->assertSame(
            ['id' => 'main', 'width' => 640, 'height' => 480],
            $client->calls[0]['payload']
        );
    }
}
