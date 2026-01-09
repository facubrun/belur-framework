<?php

namespace Belur\Providers;

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Drivers\PDODriver;
use Belur\View\BelurEngine;
use Belur\View\View;

use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class DatabaseDriverServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        match(config("database.connection", "mysql")) {
            'mysql', 'pgsql' => singleton(DatabaseDriver::class, PDODriver::class)
        };
    }
}
