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
        Schema::table('users', function (Blueprint $table) {
            // Menghapus primary key yang ada sebelumnya
            $table->dropPrimary(); // Menghapus primary key yang ada

            // Mengubah kolom id menjadi UUID dan menetapkannya sebagai primary key
            $table->uuid('id')->primary()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengubah kembali id menjadi auto-increment integer dan menetapkan primary key
            $table->increments('id')->primary()->change();
        });
    }
};
