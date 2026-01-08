<?php

use Belur\Database\DB;
use Belur\Database\Migrations\Migration;

return new class() implements Migration {
    public function up(): void {
        DB::statement('$UP');
    }

    public function down(): void {
        DB::statement('$DOWN');
    }

};
