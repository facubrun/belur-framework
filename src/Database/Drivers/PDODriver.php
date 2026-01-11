<?php

namespace Belur\Database\Drivers;

use PDO;

class PDODriver implements DatabaseDriver {
    protected ?PDO $connection = null;

    public function connect(
        string $protocol,
        string $host,
        int $port,
        string $database,
        string $username,
        string $password
    ) {
        $dsn = "$protocol:host=$host;port=$port";
        if ($database !== '') {
            $dsn .= ";dbname=$database";
        }

        $this->connection = new PDO($dsn, $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    public function close() {
        $this->connection = null;
    }

    public function statement(string $query, array $params = []): mixed {
        if (!$this->isConnected()) {
            throw new \RuntimeException('Database connection is not established');
        }
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isConnected(): bool {
        return $this->connection !== null;
    }

}
