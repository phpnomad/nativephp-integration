<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface DialogStrategy
{
    public function title(string $title): self;

    public function defaultPath(string $path): self;

    public function buttonLabel(string $label): self;

    /**
     * @param array<array{name: string, extensions: array<string>}> $filters
     */
    public function filters(array $filters): self;

    /**
     * @param array<string> $properties e.g. ['openFile','multiSelections','showHiddenFiles']
     */
    public function properties(array $properties): self;

    public function open(): mixed;

    public function save(): mixed;
}
