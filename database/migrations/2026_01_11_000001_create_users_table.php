<?php

use Belur\Database\DB;
use Belur\Database\Migrations\Migration;

return new class() implements Migration {
    public function up(): void {
        DB::statement('
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(256),
                email VARCHAR(256),
                password VARCHAR(256),
                created_at DATETIME,
                updated_at DATETIME NULL
            )
        ');
    }

    public function down(): void {
        DB::statement('DROP TABLE users');
    }
};