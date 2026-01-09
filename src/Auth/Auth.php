<?php

namespace Belur\Auth;

use Belur\Auth\Authenticators\Authenticator;

use function Belur\Helpers\app;

class Auth {
    public static function user(): ?Authenticatable {
        return app(Authenticator::class)->resolve();
    }

    public static function isGuest(): bool {
        return is_null(self::user());
    }
}