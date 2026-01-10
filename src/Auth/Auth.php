<?php

namespace Belur\Auth;

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use Belur\Auth\Authenticators\Authenticator;
use Belur\Routing\Route;

use function Belur\Helpers\app;

class Auth {
    public static function user(): ?Authenticatable {
        return app(Authenticator::class)->resolve();
    }

    public static function isGuest(): bool {
        return is_null(self::user());
    }

    public static function logout(): void {
        $user = self::user();
        if (!is_null($user)) {
            $user->logout();
        }
    }

    public static function routes() {
        Route::get('/register', [RegisterController::class, 'create']);
        Route::post('/register', [RegisterController::class, 'store']);
        Route::get('/login', fn () => [LoginController::class, 'create']);
        Route::post('/login', [LoginController::class, 'store']);
        Route::get('/logout', [LoginController::class, 'destroy']);
    }
}
