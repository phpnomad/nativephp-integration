<?php

namespace PHPNomad\NativePHP\Integration\Features;

use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class Dialog
{
    private string $title = '';
    private string $defaultPath = '';
    private string $buttonLabel = '';
    private array $filters = [];
    private array $properties = [];

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function defaultPath(string $path): self
    {
        $this->defaultPath = $path;
        return $this;
    }

    public function buttonLabel(string $label): self
    {
        $this->buttonLabel = $label;
        return $this;
    }

    /**
     * @param array<array{name: string, extensions: array<string>}> $filters
     */
    public function filters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @param array<string> $properties e.g. ['openFile','multiSelections','showHiddenFiles']
     */
    public function properties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    public function open(): mixed
    {
        $response = $this->client->post('dialog/open', $this->payload());
        return $response['result'] ?? null;
    }

    public function save(): mixed
    {
        $response = $this->client->post('dialog/save', $this->payload());
        return $response['result'] ?? null;
    }

    private function payload(): array
    {
        return array_filter([
            'title' => $this->title,
            'defaultPath' => $this->defaultPath,
            'buttonLabel' => $this->buttonLabel,
            'filters' => $this->filters,
            'properties' => $this->properties,
        ], fn ($v) => $v !== '' && $v !== []);
    }
}
