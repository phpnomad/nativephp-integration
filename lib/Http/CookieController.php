<?php

namespace PHPNomad\NativePHP\Integration\Http;

use PHPNomad\FastRoute\Component\Response as RestResponse;
use PHPNomad\Http\Enums\Method;
use PHPNomad\Http\Interfaces\Request;
use PHPNomad\Http\Interfaces\Response;
use PHPNomad\Rest\Interfaces\Controller;

class CookieController implements Controller
{
    public function getEndpoint(): string
    {
        return '/_native/api/cookie';
    }

    public function getMethod(): string
    {
        return Method::Get;
    }

    public function getResponse(Request $request): Response
    {
        return (new RestResponse())
            ->setStatus(204)
            ->setBody('');
    }
}
