<?php

use Belur\Database\Migrations\Migrator;

require_once __DIR__ . '/vendor/autoload.php';

$migrator = new Migrator(
    __DIR__ . '/database/migrations', 
    __DIR__ . '/templates/migration.php'
);

if($argv[1] == 'make:migration') {
    $migrator->make($argv[2]);
}

