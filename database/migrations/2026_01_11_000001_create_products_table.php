<?php

use Belur\Database\DB;
use Belur\Database\Migrations\Migration;

return new class() implements Migration {
    public function up(): void {
        DB::statement('CREATE TABLE products (id INT AUTO_INCREMENT PRIMARY KEY)');
    }

    public function down(): void {
        DB::statement('DROP TABLE products');
    }

};