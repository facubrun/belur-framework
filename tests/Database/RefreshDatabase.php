<?php

namespace Belur\Tests\Database;

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Drivers\PDODriver;
use Belur\Database\Model;
use PDO;
use PDOException;

use function Belur\Helpers\singleton;

trait RefreshDatabase {
    
    protected function setUp(): void {
        if ($this->driver == null) {
            $this->driver = singleton(DatabaseDriver::class, PDODriver::class);
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect(
                    'mysql',
                    'localhost',
                    3306,
                    'belur_tests',
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
}