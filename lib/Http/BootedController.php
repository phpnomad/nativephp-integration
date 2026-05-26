<?php

namespace PHPNomad\NativePHP\Integration\Http;

use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\FastRoute\Component\Response as RestResponse;
use PHPNomad\Http\Enums\Method;
use PHPNomad\Http\Interfaces\Request;
use PHPNomad\Http\Interfaces\Response;
use PHPNomad\NativePHP\Integration\Events\AppBooted;
use PHPNomad\Rest\Interfaces\Controller;
use PHPNomad\Rest\Interfaces\HasMiddleware;

class BootedController implements Controller, HasMiddleware
{
    public function __construct(
        private readonly EventStrategy $events,
        private readonly SecretGuard $secretGuard,
    ) {
    }

    public function getEndpoint(): string
    {
        return '/_native/api/booted';
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
        $this->events->broadcast(new AppBooted());

        return (new RestResponse())
            ->setStatus(204)
            ->setBody('');
    }
}
