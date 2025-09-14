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
        Schema::create('custom_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->string('job_name');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('officer_name')->nullable();
            $table->string('reporter_officer_name')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_jobs');
    }
};