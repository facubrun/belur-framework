<?php

namespace Belur\Database;

use Belur\Database\Drivers\DatabaseDriver;
use ReflectionClass;

use function Belur\Helpers\snake_case;

abstract class Model { // abstracta para que no se pueda instanciar directamente
    protected ?string $table = null;
    protected string $primaryKey = 'id';
    protected array $hidden = [];
    protected array $fillable = [];
    protected array $attributes = [];
    private static ?DatabaseDriver $driver = null;

    public static function setDatabaseDriver(DatabaseDriver $driver) {
        self::$driver = $driver;
    }

    public function __construct() {
        if (is_null($this->table)) {
            // Si no se definió la tabla, se infiere del nombre de la clase
            $className = (new ReflectionClass($this))->getShortName();
            $this->table = snake_case($className) . 's';
        }
    }

    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }

    public function save(): void {
        $databaseColumns = implode(',', array_keys($this->attributes));
        $bind = implode(',', array_fill(0, count($this->attributes), '?'));
        self::$driver->statement(
            "INSERT INTO $this->table ($databaseColumns) VALUES ($bind)",
            array_values($this->attributes)
        );
    }
}
