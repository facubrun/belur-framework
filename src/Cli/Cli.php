<?php

namespace Belur\Cli;

use Belur\App;
use Belur\Cli\Commands\MakeMigration;
use Belur\Cli\Commands\Migrate;
use Belur\Cli\Commands\MigrateRollback;
use Dotenv\Dotenv;
use Belur\Config\Config;
use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Migrations\Migrator;
use Symfony\Component\Console\Application;

use function Belur\Helpers\app;
use function Belur\Helpers\config;
use function Belur\Helpers\resourcesDirectory;
use function Belur\Helpers\singleton;

class Cli {
    public static function bootstrap(string $root): self {
        App::$root = $root;
        Dotenv::createImmutable($root)->load();
        Config::load($root . '/config');

        foreach (config('providers.cli', []) as $provider) {
            (new $provider())->registerServices();
        }

        app(DatabaseDriver::class)?->connect(
            config('database.connection'),
            config('database.host'),
            config('database.port'),
            config('database.database'),
            config('database.username'),
            config('database.password')
        );

        singleton(
            Migrator::class,
            fn () => new Migrator(
                App::$root . '/database/migrations',
                resourcesDirectory() . '/templates/migration.php',
                app(DatabaseDriver::class)
            )
        );

        return new self();
    }

    public function run() {
        $cli = new Application('Belur CLI', '1.0.0');

        $cli->addCommands([
            new MakeMigration(),
            new Migrate(),
            new MigrateRollback(),
        ]);

        $cli->run();
    }
}
