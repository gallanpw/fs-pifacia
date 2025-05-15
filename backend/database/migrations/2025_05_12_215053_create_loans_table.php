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
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('funder_id');
            $table->uuid('farmer_id');
            $table->json('data');  // Menyimpan data loans
            $table->boolean('is_active')->default(true);
            $table->string('attachment_url')->nullable();
            $table->timestamps();
            $table->softDeletes();  // Soft delete

            // Relasi dengan funder dan farmer
            $table->foreign('funder_id')->references('id')->on('funders')->onDelete('cascade');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
