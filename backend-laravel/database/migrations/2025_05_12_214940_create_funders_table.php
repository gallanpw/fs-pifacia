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
        Schema::create('funders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('data');  // Menyimpan data funders
            $table->boolean('is_active')->default(true);
            $table->string('attachment_url')->nullable();
            $table->timestamps();
            $table->softDeletes();  // Soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funders');
    }
};
