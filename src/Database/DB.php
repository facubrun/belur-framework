<?php

namespace Belur\Database;

use function Belur\Helpers\app;

class DB {
    public static function statement(string $query, array $params = []) {
        return app()->database->statement($query, $params);
    }
}
