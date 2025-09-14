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
        // Drop existing salary_sheets table if it exists
        if (Schema::hasTable('salary_sheets')) {
            Schema::drop('salary_sheets');
        }
        
        Schema::create('salary_sheet', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_no')->unique();
            $table->unsignedBigInteger('job_id');
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('custom_jobs')->onDelete('cascade');
            $table->index(['job_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_sheet');
    }
};