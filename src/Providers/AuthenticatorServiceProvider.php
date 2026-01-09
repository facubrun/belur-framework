<?php

namespace Belur\Providers;

use Belur\Auth\Authenticators\Authenticator;
use Belur\Auth\Authenticators\SessionAuthenticator;

use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class AuthenticatorServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        match (config('auth.method', 'session')) {
            'session' => singleton(Authenticator::class, SessionAuthenticator::class),
        };
    }
}
