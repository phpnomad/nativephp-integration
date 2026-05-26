<?php

namespace PHPNomad\NativePHP\Integration\Strategies;

use PHPNomad\NativePHP\Integration\Interfaces\AlertStrategy as AlertStrategyContract;
use PHPNomad\NativePHP\Integration\Contracts\NativeClient;

class AlertStrategy implements AlertStrategyContract
{
    private string $title = '';
    private string $message = '';
    private string $type = 'info';
    private ?array $buttons = null;
    private int $defaultId = 0;
    private string $detail = '';

    public function __construct(private readonly NativeClient $client)
    {
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function buttons(array $buttons, int $defaultId = 0): self
    {
        $this->buttons = $buttons;
        $this->defaultId = $defaultId;
        return $this;
    }

    public function detail(string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    public function show(string $message): array
    {
        $this->message = $message;

        return $this->client->post('alert/message', array_filter([
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'buttons' => $this->buttons,
            'defaultId' => $this->defaultId,
            'detail' => $this->detail,
        ], fn ($v) => $v !== null && $v !== '' && $v !== []));
    }

    public function error(string $message): array
    {
        $this->message = $message;

        return $this->client->post('alert/error', [
            'title' => $this->title,
            'message' => $this->message,
        ]);
    }
}
