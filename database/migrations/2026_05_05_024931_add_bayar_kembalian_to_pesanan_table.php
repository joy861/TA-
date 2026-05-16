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
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'bayar')) {
                $table->integer('bayar')->default(0)->after('metode_pembayaran');
            }

            if (!Schema::hasColumn('pesanan', 'kembalian')) {
                $table->integer('kembalian')->default(0)->after('bayar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'kembalian')) {
                $table->dropColumn('kembalian');
            }

            if (Schema::hasColumn('pesanan', 'bayar')) {
                $table->dropColumn('bayar');
            }
        });
    }
};