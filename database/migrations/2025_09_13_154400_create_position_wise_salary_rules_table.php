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
        Schema::create('position_wise_salary_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('promoter_positions')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Ensure no duplicate rules for the same position
            $table->unique('position_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_wise_salary_rules');
    }
};