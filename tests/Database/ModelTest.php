<?php

namespace Belur\Tests\Database;

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Drivers\PDODriver;
use Belur\Database\Model;
use PDOException;
use PHPUnit\Framework\TestCase;

class MockModel extends Model {
    //
}

class ModelTest extends TestCase {
    protected ?DatabaseDriver $driver = null;
    
    public function setUp(): void {
        if ($this->driver == null) {
            $this->driver = new PDODriver();
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect(
                    'mysql',
                    'localhost',
                    3306,
                    'belur_framework',
                    'root',
                    ''
                );
            } catch (PDOException $e) {
                $this->markTestSkipped('Database connection could not be established: ' . $e->getMessage());
            }
        }
    }

    protected function tearDown(): void {
        $this->driver->statement("DROP DATABASE IF EXISTS belur_tests");
        $this->driver->statement("CREATE DATABASE belur_tests");
    }

    private function createTestTable($name, $columns, $withTimestamps = false) {
        $sql = "CREATE TABLE $name (id INT AUTO_INCREMENT PRIMARY KEY, "
            . implode(', ', array_map(fn ($col) => "$col VARCHAR(255)", $columns));
        if ($withTimestamps) {
            $sql .= ", created_at DATETIME, updated_at DATETIME NULL";
        }
        $sql .= ")";
        $this->driver->statement($sql);
    }

    public function test_save_basic_model_with_attributes() {
        $this->createTestTable('mock_models', ['test','name'], true);
        $model = new MockModel();
        $model->test = 'test';
        $model->name = 'name';
        $model->save();

        $rows = $this->driver->statement("SELECT * FROM mock_models");

        $expected = [
            'id' => 1,
            'test' => 'test',
            'name' => 'name',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => null,
        ];

        $this->assertEquals($expected, $rows[0]);
        $this->assertEquals(1, count($rows));
    }
}
