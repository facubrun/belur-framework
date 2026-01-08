<?php

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Drivers\PDODriver;
use Belur\Database\Migrations\Migrator;

use function Belur\Helpers\singleton;

require_once __DIR__ . '/vendor/autoload.php';

// Solo crear el driver si el comando lo necesita
$driver = null;
if(in_array($argv[1], ['migrate', 'rollback'])) {
    $driver = singleton(DatabaseDriver::class, PDODriver::class);
    $driver->connect(
        'mysql',
        'localhost',
        3306,
        'belur_framework',
        'root',
        ''
    );
}

$migrator = new Migrator(
    __DIR__ . '/database/migrations', 
    __DIR__ . '/templates/migration.php',
    $driver
);

if($argv[1] == 'make:migration') {
    $migrator->make($argv[2]);

} else if($argv[1] == 'migrate') {
    $migrator->migrate();

} else if ($argv[1] == 'rollback') {
    $steps = null;
    if (count($argv) == 4 ) {
        $steps = $argv[2] == '--step' ? $argv[3] : null;
    }
    $migrator->rollback($steps);
}

