<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    DB::statement("ALTER TABLE detail_pesanan MODIFY is_new TINYINT(1) DEFAULT 0");
}

public function down(): void
{
    DB::statement("ALTER TABLE detail_pesanan MODIFY is_new TINYINT(1) DEFAULT 1");
}
};
