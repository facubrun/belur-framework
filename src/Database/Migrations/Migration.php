<?php

namespace Belur\Database\Migrations;

interface Migration {
    public function up(): void;
    public function down(): void;
}
