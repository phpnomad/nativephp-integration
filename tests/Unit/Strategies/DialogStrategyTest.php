<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit\Strategies;

use PHPNomad\NativePHP\Integration\Strategies\DialogStrategy;
use PHPNomad\NativePHP\Integration\Tests\Support\SpyClient;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class DialogStrategyTest extends TestCase
{
    public function test_open_returns_the_result_path(): void
    {
        $client = new SpyClient();
        $client->stub('dialog/open', ['result' => ['/tmp/picked.txt']]);

        $result = (new DialogStrategy($client))
            ->title('Pick')
            ->filters([['name' => 'Text', 'extensions' => ['txt']]])
            ->properties(['openFile'])
            ->open();

        $this->assertSame(['/tmp/picked.txt'], $result);

        $call = $client->calls[0];
        $this->assertSame('dialog/open', $call['endpoint']);
        $this->assertSame('Pick', $call['payload']['title']);
        $this->assertSame([['name' => 'Text', 'extensions' => ['txt']]], $call['payload']['filters']);
        $this->assertSame(['openFile'], $call['payload']['properties']);
    }

    public function test_save_returns_the_chosen_path(): void
    {
        $client = new SpyClient();
        $client->stub('dialog/save', ['result' => '/tmp/file.txt']);

        $result = (new DialogStrategy($client))->defaultPath('/tmp')->save();
        $this->assertSame('/tmp/file.txt', $result);
    }
}
