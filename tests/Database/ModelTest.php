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

class MockModelFillable extends Model {
    protected ?string $table = 'mock_models';
    protected array $fillable = ['test', 'name', 'email'];
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

    private function createTestTable($name, $columns, $withTimestamps = true) {
        $sql = "CREATE TABLE $name (id INT AUTO_INCREMENT PRIMARY KEY, "
            . implode(', ', array_map(fn ($col) => "$col VARCHAR(255)", $columns));
        if ($withTimestamps) {
            $sql .= ", created_at DATETIME, updated_at DATETIME NULL";
        }
        $sql .= ")";
        $this->driver->statement($sql);
    }

    public function test_save_basic_model_with_attributes() {
        $this->createTestTable('mock_models', ['test','name']);
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

    /**
     * @depends test_save_basic_model_with_attributes
     */
    public function test_find_model() {
        $this->createTestTable('mock_models', ['name', 'email']);

        $expected = [
            [
                'id' => 1,
                'name' => 'name',
                'email' => 'email@test.uy',
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'name2',
                'email' => 'email2@test.uy',
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => null,
            ],
        ];

        foreach ($expected as $columns) {
            $model = new MockModel();
            $model->name = $columns['name'];
            $model->email = $columns['email'];
            $model->save();
        }

        foreach ($expected as $columns) {
            $model = new MockModel();
            foreach ($columns as $col => $value) {
                $model->{$col} = $value;
            }
            $this->assertEquals($model, MockModel::find($columns['id']));
        }
        $this->assertNull(MockModel::find(9999));
    }

    /**
     * @depends test_save_basic_model_with_attributes
     */
    public function test_create_model_with_no_fillable_attributes_throws_error() {
        $this->expectException(\Error::class);
        
        MockModel::create(['test' => 'error']);
    }

    /**
     * @depends test_create_model_with_no_fillable_attributes_throws_error
     */
    public function test_create_model() {
        $this->createTestTable('mock_models', ['name', 'email']);

        $model = MockModelFillable::create(['name' => 'name', 'email' => 'email@test.com']);

        $this->assertEquals(1, count($this->driver->statement("SELECT * FROM mock_models")));
        $this->assertEquals('name', $model->name);
        $this->assertEquals('email@test.com', $model->email);
    }

    /**
     * @depends test_create_model
     */
    public function test_all() {
        $this->createTestTable('mock_models', ['name', 'email']);

        $data = [
            ['name' => 'name', 'email' => 'test@mail.com'],
            ['name' => 'name', 'email' => 'test@mail.com'],
            ['name' => 'name', 'email' => 'test@mail.com'],
        ];

        foreach ($data as $row) {
            MockModelFillable::create($row);
        }

        $models = MockModelFillable::all();

        $this->assertEquals(3, count($models));

        foreach ($models as $model) {
            $this->assertEquals('name', $model->name);
            $this->assertEquals('test@mail.com', $model->email);
        }
    }

    /**
     * @depends test_create_model
     */
    public function test_where_and_first_where() {
        $this->createTestTable('mock_models', ['name', 'email']);

        $data = [
            ['name' => 'repetido', 'email' => 'rep1@mail.com'],
            ['name' => 'repetido', 'email' => 'rep2@mail.com'],
        ];

        // Insertar los datos en la base de datos
        foreach ($data as $row) {
            MockModelFillable::create($row);
        }

        $where = MockModelFillable::where('name', 'repetido');
        $firstWhere = MockModelFillable::firstWhere('name', 'repetido');

        $this->assertEquals(2, count($where));
        $this->assertEquals('repetido', $where[0]->name);
        $this->assertEquals('repetido', $where[1]->name);

        $this->assertEquals('rep1@mail.com', $firstWhere->email);
    }

    /**
     * @depends test_create_model
     * @depends test_find_model
     */
    public function test_update_model() {
        $this->createTestTable('mock_models', ['name', 'email']);

        MockModelFillable::create(['name' => 'name', 'email' => 'email@test.com']);

        $model = MockModelFillable::find(1);

        $model->name = 'name updated';
        $model->email = 'updated@test.com';

        $model->update();

        $rows = $this->driver->statement("SELECT name, email FROM mock_models");
        $this->assertEquals('name updated', $rows[0]['name']);
        $this->assertEquals('updated@test.com', $rows[0]['email']);
        $this->assertEquals(1, count($rows));
    }

    /**
     * @depends test_create_model
     * @depends test_find_model
     */
    public function test_delete_model() {
        $this->createTestTable('mock_models', ['name', 'email']);

        MockModelFillable::create(['name' => 'name', 'email' => 'email@test.com']);

        $model = MockModelFillable::find(1);

        $model->delete();

        $rows = $this->driver->statement("SELECT name, email FROM mock_models");
        $this->assertEquals(0, count($rows));
    }
}
