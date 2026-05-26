<?php

namespace SandboxApp;

use PHPNomad\Auth\Interfaces\CurrentContextResolverStrategy;
use PHPNomad\Auth\Interfaces\User;
use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Logger\Interfaces\LoggerStrategy;

class ContextInitializer implements HasClassDefinitions, CanSetContainer
{
    use HasSettableContainer;

    public function getClassDefinitions(): array
    {
        return [
            RestContextResolver::class => CurrentContextResolverStrategy::class,
            StderrLogger::class => LoggerStrategy::class,
            GuestUser::class => User::class,
        ];
    }
}
