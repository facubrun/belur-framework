<?php

namespace Belur\Auth;

use Belur\Auth\Authenticators\Authenticator;
use Belur\Database\Model;

use function Belur\Helpers\app;

class Authenticatable extends Model {
    public function id(): int|string {
        return $this->{$this->primaryKey};
    }

    public function login() {
        app(Authenticator::class)->login($this);
    }

    public function logout() {
        app(Authenticator::class)->logout($this);
    }

    public function isAuthenticated(): bool {
        return app(Authenticator::class)->isAuthenticated($this);
    }

    public static function resolve(): ?Authenticatable {
        return app(Authenticator::class)->resolve();
    }

}
