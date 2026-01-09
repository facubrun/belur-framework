<?php

namespace Belur\Database\Migrations;

use Belur\Database\Drivers\DatabaseDriver;

use function Belur\Helpers\snake_case;

class Migrator {

    public function __construct(
        private string $migrationsPath,
        private string $templatesPath,
        private ?DatabaseDriver $driver,
        private bool $logProgress = true
    ) {
        $this->migrationsPath = $migrationsPath;
        $this->templatesPath = $templatesPath;
        $this->driver = $driver;
    }

    private function log(string $message) {
        echo $message . "\n";
    }

    private function createMigrationTableIfNotExists() {
        $this->driver->statement("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY, 
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function migrate() {
        $this->createMigrationTableIfNotExists();
        
        $migrated = $this->driver->statement("SELECT * FROM migrations");
        $migrations = glob("$this->migrationsPath/*.php");

        if (count($migrated) >= count($migrations)) {
            $this->log("Nothing to migrate"); // No new migrations
            return;
        }

        foreach (array_slice($migrations, count($migrated)) as $file) {
            $migration = require $file;
            $migration->up();
            $name = basename($file);
            $this->driver->statement("INSERT INTO migrations (migration) VALUES (?)", [$name]);
            $this->log("Migrated: $name");
        }
    }

    public function make(string $migrationName) {
        $migrationName = snake_case($migrationName);

        $template =  file_get_contents($this->templatesPath);

        if (preg_match("/create_.*_table/", $migrationName)) {
            $table = preg_replace_callback(
                "/create_(.*)_table/",
                fn ($matches) => $matches[1],
                $migrationName
            ); // match[0] is full string, match[1] is first group
            $template = str_replace('$UP', "CREATE TABLE $table (id INT AUTO_INCREMENT PRIMARY KEY)", $template);
            $template = str_replace('$DOWN', "DROP TABLE $table", $template);

        } elseif (preg_match("/.*_(to|from)_.*_table/", $migrationName)) {
            $table = preg_replace_callback(
                "/(.*)_(to|from)_(.*)_table/",
                fn ($matches) => $matches[3],
                $migrationName
            );
            $template = preg_replace('/\$UP|\$DOWN/', "ALTER TABLE $table", $template);

        } else {
            $template = preg_replace_callback("DB::statement.*/", fn ($matches) => "// " . trim($matches[0]), $template);
        }

        $date = date('Y_m_d');
        $id = 0;

        foreach (glob("$this->migrationsPath/*.php") as $file) {
            if (str_starts_with(basename($file), $date)) {
                $id++;
            }
        }
        $fileName = sprintf(
            "%s_%06d_%s.php",
            $date,
            $id,
            $migrationName
        );

        file_put_contents("$this->migrationsPath/$fileName", $template);

        return $fileName;
    }

    public function rollback(?int $steps = null) {
        $this->createMigrationTableIfNotExists();
        
        $migrated = $this->driver->statement("SELECT * FROM migrations ORDER BY id DESC");
        $pending = count($migrated);

        if ($pending == 0) {
            $this->log("Nothing to rollback");
            return;
        }

        if (is_null($steps) || $steps > $pending) {
            $steps = $pending;
        }

        // Tomar las últimas N migraciones de la base de datos (ya están ordenadas DESC)
        $migrationsToRollback = array_slice($migrated, 0, $steps);

        foreach ($migrationsToRollback as $record) {
            $migrationName = $record['migration'];
            $file = "$this->migrationsPath/$migrationName";
            
            if (!file_exists($file)) {
                $this->log("Migration file not found: $migrationName");
                continue;
            }
            
            $migration = require $file;
            $migration->down();
            $this->driver->statement("DELETE FROM migrations WHERE migration = ?", [$migrationName]);
            $this->log("Rolled back: $migrationName");
        }
    }
}
