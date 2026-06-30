<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('bayar_cash')->nullable()->after('bayar');
            $table->unsignedBigInteger('bayar_elektronik')->nullable()->after('bayar_cash');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['bayar_cash', 'bayar_elektronik']);
        });
    }
};