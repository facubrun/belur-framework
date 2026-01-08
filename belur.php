<?php

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Drivers\PDODriver;
use Belur\Database\Migrations\Migrator;

use function Belur\Helpers\singleton;

require_once __DIR__ . '/vendor/autoload.php';

$driver = singleton(DatabaseDriver::class, PDODriver::class);

$driver->connect(
    'mysql',
    'localhost',
    3306,
    'belur_framework',
    'root',
    ''
);

$migrator = new Migrator(
    __DIR__ . '/database/migrations', 
    __DIR__ . '/templates/migration.php',
    $driver
);

if($argv[1] == 'make:migration') {
    $migrator->make($argv[2]);
} else if($argv[1] == 'migrate') {
    $migrator->migrate();
}

