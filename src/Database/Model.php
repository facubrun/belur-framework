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
    protected bool $insertTimestamps = true;
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

    public function __sleep() {
        foreach ($this->hidden as $hide) {
            unset($this->attributes[$hide]);
        }

        return array_keys(get_object_vars($this));
    }

    protected function setAttributes(array $attributes): static {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }
        
        return $this;
    }

    protected function massAssign(array $attributes): static {
        if (count($this->fillable) == 0) {
            throw new \Error("Model " . get_class($this) . " does not have fillable attributes defined.");
        }
        
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->__set($key, $value);
            }
        }

        return $this;
    }

    public function save(): static {
        if ($this->insertTimestamps) {
            $this->attributes['created_at'] = date('Y-m-d H:m:s');
        }
        $databaseColumns = implode(',', array_keys($this->attributes));
        $bind = implode(',', array_fill(0, count($this->attributes), '?'));
        self::$driver->statement(
            "INSERT INTO $this->table ($databaseColumns) VALUES ($bind)",
            array_values($this->attributes)
        );

        $this->{$this->primaryKey} = self::$driver->lastInsertId();

        return $this;
    }

    public static function create(array $attributes): static {
        return (new static())->massAssign($attributes)->save();
    }

    public function toArray(): array {
        return array_filter(
            $this->attributes,
            fn ($key) => !in_array($key, $this->hidden),
            ARRAY_FILTER_USE_KEY
        );
    }

    public static function first(): ?static {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table LIMIT 1");
        
        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    public static function find(string|int $id): ?static {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $model->primaryKey = ?", [$id]);
        
        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    public static function all(): array {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table");
        
        if (count($rows) == 0) {
            return [];
        }

        $models = [];
        foreach ($rows as $row) {
            $models[] = (new static())->setAttributes($row);
        }
        
        return $models;
    }

    public static function where(string $column, mixed $value): array {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $column = ?", [$value]);
        
        if (count($rows) == 0) {
            return [];
        }

        $models = [];
        foreach ($rows as $row) {
            $models[] = (new static())->setAttributes($row);
        }
        
        return $models;
    }

    public static function firstWhere(string $column, mixed $value): ?static {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $column = ? LIMIT 1", [$value]);
        
        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    public function update(): static {
        if ($this->insertTimestamps) {
            $this->attributes['updated_at'] = date('Y-m-d H:m:s');
        }

        $databaseColumns = array_keys($this->attributes);
        $bind = implode(',', array_map(fn ($col) => "$col = ?", $databaseColumns));
        $id = $this->attributes[$this->primaryKey];
        self::$driver->statement(
            "UPDATE $this->table SET $bind WHERE $this->primaryKey = ?",
            array_merge(array_values($this->attributes), [$id])
        );

        return $this;
    }

    public function delete() {
        $id = $this->attributes[$this->primaryKey];
        self::$driver->statement(
            "DELETE FROM $this->table WHERE $this->primaryKey = ?",
            [$id]
        );

        return $this;
    }
}
