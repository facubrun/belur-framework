<?php

namespace Belur\Providers;

use Belur\Session\PhpNativeSessionStorage;
use Belur\Session\SessionStorage;

use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class SessionStorageServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        match(config("session.storage", "native")) {
            "native" => singleton(SessionStorage::class, PhpNativeSessionStorage::class)
        };
    }
}
