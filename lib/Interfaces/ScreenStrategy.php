<?php

namespace PHPNomad\NativePHP\Integration\Interfaces;

interface ScreenStrategy
{
    /**
     * Returns the raw response from the Electron screen/displays endpoint.
     * Shape is platform-defined — typically a dict with a 'displays' key
     * containing the list of display descriptors.
     *
     * @return array<string, mixed>
     */
    public function displays(): array;

    /**
     * @return array<string, mixed>
     */
    public function primaryDisplay(): array;

    /**
     * @return array<string, mixed>
     */
    public function cursorPosition(): array;
}
