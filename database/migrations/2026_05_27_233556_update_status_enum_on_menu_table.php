<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `menu` MODIFY `status` ENUM('tersedia', 'habis') NOT NULL DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `menu` MODIFY `status` ENUM('tersedia') NOT NULL DEFAULT 'tersedia'");
    }
};