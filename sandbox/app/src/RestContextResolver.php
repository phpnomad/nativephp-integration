<?php

namespace SandboxApp;

use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Interfaces\CurrentContextResolverStrategy;

/**
 * Constant-Rest context. The HTTP front controller serves only REST routes,
 * so a static resolver is sufficient for this PoC. A real app might inspect
 * the request shape to choose between Rest/Web/Ajax.
 */
class RestContextResolver implements CurrentContextResolverStrategy
{
    public function getCurrentContext(): string
    {
        return SessionContexts::Rest;
    }
}
