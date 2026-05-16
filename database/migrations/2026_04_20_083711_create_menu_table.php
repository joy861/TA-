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
        Schema::create('menu', function (Blueprint $table) {
    $table->id('id_menu');
    $table->string('nama_menu');
    $table->integer('harga');
    $table->unsignedBigInteger('id_kategori');
    $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
    $table->timestamps();

    $table->foreign('id_kategori')
          ->references('id_kategori')->on('kategori')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
