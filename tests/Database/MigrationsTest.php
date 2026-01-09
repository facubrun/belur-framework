<?php

namespace Belur\Tests\Database;

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Migrations\Migrator;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

class MigrationsTest extends TestCase {
    use RefreshDatabase {
        setUp as protected dbSetUp;
        tearDown as protected dbTearDown;
    }

    protected ?DatabaseDriver $driver = null;
    protected string $templatesPath = __DIR__ . '/templates/migration.php';
    protected string $migrationsPath = __DIR__ . '/migrations';
    protected string $expectedPath = __DIR__ . '/expected';
    protected Migrator $migrator;

    protected function setUp(): void {
        if (!file_exists($this->migrationsPath)) {
            mkdir($this->migrationsPath);
        }

        $this->migrator = new Migrator(
            $this->migrationsPath,
            $this->templatesPath,
            null,
            false
        );
    }

    protected function setUpDatabase(): void {
        $this->dbSetUp();
        
        $this->migrator = new Migrator(
            $this->migrationsPath,
            $this->templatesPath,
            $this->driver
        );
    }

    protected function tearDown(): void {
        if (PHP_OS_FAMILY === 'Windows') {
            shell_exec("rmdir /s /q " . escapeshellarg($this->migrationsPath));
        } else {
            shell_exec("rm -rf " . escapeshellarg($this->migrationsPath));
        }

        if ($this->driver !== null) {
            $this->dbTearDown();
        }
    }

    public static function migrationNames() {
        return [
            ['create_products_table', __DIR__ . '/expected/create_products_table.php'],
            ['add_category_to_products_table', __DIR__ . '/expected/add_category_to_products_table.php'],
            ['remove_price_from_products_table', __DIR__ . '/expected/remove_price_from_products_table.php'],
        ];
    }

    /**
     * @dataProvider migrationNames
     */
    public function test_create_migration_files($name, $expectedMigationFile) {
        $expectedName = sprintf("%s_%06d_%s.php", date('Y_m_d'), 0, $name);
        $this->migrator->make($name);

        $file = $this->migrationsPath . '/' . $expectedName;
        $this->assertFileExists($file);
        $this->assertFileEquals($expectedMigationFile, $file);
    }

    /**
     * @depends test_create_migration_files
     */
    public function test_migrate_files() {
        $this->setUpDatabase();

        $tables = ['users', 'products', 'sellers'];
        $migrated = [];

        foreach ($tables as $table) {
            $migrated[] = $this->migrator->make("create_{$table}_table");
        }

        $this->migrator->migrate();

        $rows = $this->driver->statement("SELECT * FROM migrations");

        $this->assertCount(3, $rows);
        $this->assertEquals($migrated, array_column($rows, 'migration'));

        foreach ($tables as $table) {
            $tableExists = $this->driver->statement("SHOW TABLES LIKE ?", [$table]);
            $this->assertCount(1, $tableExists);
        }
    }

    public function test_rollback_migrations() {
        $this->setUpDatabase();
        
        $tables = ['users', 'products', 'sellers', 'orders', 'categories'];

        // Migrar las tablas
        $migrated = [];

        foreach ($tables as $table) {
            $migrated[] = $this->migrator->make("create_{$table}_table");
        }

        $this->migrator->migrate();

        // Deshacer la última migración
        $this->migrator->rollback(1);
        $rows = $this->driver->statement("SELECT * FROM migrations");
        $this->assertCount(4, $rows);
        $this->assertEquals(
            array_slice($migrated, 0, 4),
            array_column($rows, 'migration')
        );

        try {
            $table = $tables[count($tables) - 1];
            $this->driver->statement("SELECT * FROM $table");
            $this->fail("The table $table was not deleted after rollback.");
        } catch (PDOException $e) {
            $this->assertTrue(true);
        }

        // Deshacer todas las migraciones restantes
        $this->migrator->rollback();
        $rows = $this->driver->statement("SELECT * FROM migrations");
        $this->assertCount(0, $rows);
    }
}
