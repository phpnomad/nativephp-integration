<?php

namespace PHPNomad\NativePHP\Integration\DataObjects;

/**
 * Declarative description of a window. Apps register definitions with the
 * WindowManager; the manager translates each definition into a window/open
 * call at the right lifecycle moment (typically right after AppBooted).
 */
class WindowDefinition
{
    public function __construct(
        public readonly string $id,
        public readonly string $url,
        public readonly string $title = '',
        public readonly int $width = 800,
        public readonly int $height = 600,
        public readonly ?int $x = null,
        public readonly ?int $y = null,
        public readonly bool $frame = true,
        public readonly bool $resizable = true,
        public readonly bool $alwaysOnTop = false,
        public readonly bool $openOnBoot = true,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'width' => $this->width,
            'height' => $this->height,
            'x' => $this->x,
            'y' => $this->y,
            'frame' => $this->frame,
            'resizable' => $this->resizable,
            'alwaysOnTop' => $this->alwaysOnTop,
        ], fn ($v) => $v !== null && $v !== '');
    }
}
