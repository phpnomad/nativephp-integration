<?php

namespace PHPNomad\NativePHP\Integration\Http;

use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\FastRoute\Component\Response as RestResponse;
use PHPNomad\Http\Enums\Method;
use PHPNomad\Http\Interfaces\Request;
use PHPNomad\Http\Interfaces\Response;
use PHPNomad\NativePHP\Integration\Events\EventTranslator;
use PHPNomad\Rest\Interfaces\Controller;
use PHPNomad\Rest\Interfaces\HasMiddleware;

/**
 * Receives Electron's outbound event webhooks and translates them into
 * PHPNomad events that domain handlers can subscribe to without ever
 * touching the wire format.
 */
class EventsController implements Controller, HasMiddleware
{
    public function __construct(
        private readonly EventStrategy $events,
        private readonly EventTranslator $translator,
        private readonly SecretGuard $secretGuard,
    ) {
    }

    public function getEndpoint(): string
    {
        return '/_native/api/events';
    }

    public function getMethod(): string
    {
        return Method::Post;
    }

    public function getMiddleware(Request $request): array
    {
        return [$this->secretGuard];
    }

    public function getResponse(Request $request): Response
    {
        $body = json_decode($request->getBody(), true) ?: [];
        $sourceEventName = (string) ($body['event'] ?? '');
        $payload = (array) ($body['payload'] ?? []);

        if ($sourceEventName !== '') {
            $this->events->broadcast($this->translator->translate($sourceEventName, $payload));
        }

        return (new RestResponse())
            ->setStatus(204)
            ->setBody('');
    }
}
