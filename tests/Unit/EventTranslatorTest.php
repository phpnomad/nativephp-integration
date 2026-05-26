<?php

namespace PHPNomad\NativePHP\Integration\Tests\Unit;

use PHPNomad\NativePHP\Integration\Events\AppOpenedFromUrl;
use PHPNomad\NativePHP\Integration\Events\EventTranslator;
use PHPNomad\NativePHP\Integration\Events\UnknownNativeEvent;
use PHPNomad\NativePHP\Integration\Events\WindowFocused;
use PHPNomad\NativePHP\Integration\Events\WindowResized;
use PHPNomad\NativePHP\Integration\Tests\TestCase;

class EventTranslatorTest extends TestCase
{
    public function test_known_event_translation(): void
    {
        $translator = new EventTranslator();

        $event = $translator->translate(
            '\\Native\\Laravel\\Events\\Windows\\WindowFocused',
            ['main']
        );

        $this->assertInstanceOf(WindowFocused::class, $event);
        $this->assertSame('main', $event->windowId());
    }

    public function test_resized_payload_unpacks_dimensions(): void
    {
        $event = (new EventTranslator())->translate(
            '\\Native\\Laravel\\Events\\Windows\\WindowResized',
            ['main', 1024, 768]
        );

        $this->assertInstanceOf(WindowResized::class, $event);
        $this->assertSame('main', $event->windowId());
        $this->assertSame(1024, $event->width());
        $this->assertSame(768, $event->height());
    }

    public function test_opened_from_url(): void
    {
        $event = (new EventTranslator())->translate(
            '\\Native\\Laravel\\Events\\App\\OpenedFromURL',
            ['nomadic://path']
        );

        $this->assertInstanceOf(AppOpenedFromUrl::class, $event);
        $this->assertSame('nomadic://path', $event->url());
    }

    public function test_unknown_falls_back_to_generic_event(): void
    {
        $event = (new EventTranslator())->translate(
            '\\Native\\Laravel\\Events\\SomeWeirdNewThing',
            ['anything']
        );

        $this->assertInstanceOf(UnknownNativeEvent::class, $event);
        $this->assertSame('\\Native\\Laravel\\Events\\SomeWeirdNewThing', $event->sourceEventName);
        $this->assertSame(['anything'], $event->payload);
    }
}
