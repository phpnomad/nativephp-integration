<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Features;

use PHPNomad\NativePHP\Integration\Features\Settings;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class SettingsTest extends TestCase
{
    public function test_get_set_forget(): void
    {
        $client = new SpyClient();
        $client->stub('settings/theme', ['value' => 'dark']);

        $s = new Settings($client);
        $this->assertSame('dark', $s->get('theme'));
        $s->set('theme', 'light');
        $s->forget('theme');

        $this->assertSame(
            [
                ['method' => 'GET',    'endpoint' => 'settings/theme', 'payload' => []],
                ['method' => 'POST',   'endpoint' => 'settings/theme', 'payload' => ['value' => 'light']],
                ['method' => 'DELETE', 'endpoint' => 'settings/theme', 'payload' => []],
            ],
            $client->calls
        );
    }

    public function test_keys_with_special_characters_are_url_encoded(): void
    {
        $client = new SpyClient();
        (new Settings($client))->set('user/name', 'alex');
        $this->assertSame('settings/user%2Fname', $client->calls[0]['endpoint']);
    }
}
