<?php

namespace Belur\Database;

use Belur\Database\Drivers\DatabaseDriver;

use function Belur\Helpers\app;

class DB {
    public static function statement(string $query, array $params = []) {
        return app(DatabaseDriver::class)->statement($query, $params);
    }
}
