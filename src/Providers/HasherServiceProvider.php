<?php

namespace Belur\Providers;

use Belur\Crypto\Bcrypt;
use Belur\Crypto\Hasher;

use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class HasherServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        match(config('hashing.hasher', 'bcrypt')) {
            'bcrypt' => singleton(Hasher::class, Bcrypt::class),
        };
    }
}
