<?php

namespace PHPNomad\NativePHP\Integration;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;
use PHPNomad\NativePHP\Integration\DataObjects\WindowDefinition;
use PHPNomad\NativePHP\Integration\Strategies\WindowStrategy;

class WindowManager
{
    /** @var array<string, WindowDefinition> */
    private array $definitions = [];

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function register(WindowDefinition $definition): self
    {
        $this->definitions[$definition->id] = $definition;
        return $this;
    }

    /**
     * @return array<string, WindowDefinition>
     */
    public function all(): array
    {
        return $this->definitions;
    }

    public function definition(string $id): ?WindowDefinition
    {
        return $this->definitions[$id] ?? null;
    }

    public function open(string $id): void
    {
        $def = $this->definition($id);
        if ($def === null) {
            throw new \InvalidArgumentException("No window registered with id [{$id}]");
        }

        $window = $this->build($id);
        $window
            ->url($def->url)
            ->title($def->title)
            ->size($def->width, $def->height)
            ->frameless(! $def->frame)
            ->resizable($def->resizable)
            ->alwaysOnTop($def->alwaysOnTop);

        if ($def->x !== null && $def->y !== null) {
            $window->position($def->x, $def->y);
        }

        $window->open();
    }

    public function openAllOnBoot(): void
    {
        foreach ($this->definitions as $def) {
            if ($def->openOnBoot) {
                $this->open($def->id);
            }
        }
    }

    public function close(string $id): void
    {
        $this->build($id)->close();
    }

    private function build(string $id): WindowStrategy
    {
        return (new WindowStrategy($this->client))->id($id);
    }
}
