<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('pertanyaan_keamanan')->nullable()->after('role');
        $table->string('jawaban_keamanan')->nullable()->after('pertanyaan_keamanan');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['pertanyaan_keamanan', 'jawaban_keamanan']);
    });
}
};
