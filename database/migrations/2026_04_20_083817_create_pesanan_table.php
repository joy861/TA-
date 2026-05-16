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
        Schema::create('pesanan', function (Blueprint $table) {
    $table->id('id_pesanan');
    $table->dateTime('tanggal');
    $table->unsignedBigInteger('id_meja');
    $table->unsignedBigInteger('id_user');
    $table->integer('total_harga')->default(0);
    $table->enum('status', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
    $table->timestamps();

    $table->foreign('id_meja')
          ->references('id_meja')->on('meja')
          ->onDelete('cascade');

    $table->foreign('id_user')
          ->references('id_user')->on('users')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
