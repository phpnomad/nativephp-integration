<?php

namespace SandboxApp;

use PHPNomad\Auth\Interfaces\Action;
use PHPNomad\Auth\Interfaces\User;

/**
 * Anonymous "user" the container resolves whenever something asks for
 * a PHPNomad User. A real app would replace this with a session-backed
 * user. For desktop apps that ship a single-user installation, a Guest
 * is usually all you need.
 */
class GuestUser implements User
{
    public function getId(): string
    {
        return 'guest';
    }

    public function getEmail(): string
    {
        return '';
    }

    public function canDoAction(Action $action): bool
    {
        return true;
    }

    public function getIdentity(): array
    {
        return ['id' => 'guest'];
    }
}
