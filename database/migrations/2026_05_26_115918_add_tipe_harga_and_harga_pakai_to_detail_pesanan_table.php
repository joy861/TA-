<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->string('tipe_harga', 10)->default('normal')->after('jumlah_awal');
            $table->integer('harga_pakai')->default(0)->after('tipe_harga');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropColumn(['tipe_harga', 'harga_pakai']);
        });
    }
};