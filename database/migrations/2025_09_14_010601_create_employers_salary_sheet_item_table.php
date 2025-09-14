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
        Schema::create('employers_salary_sheet_item', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique(); // Unique identifier for each item
            $table->string('location')->nullable();
            $table->unsignedBigInteger('position_id');
            $table->json('attendance_data')->nullable(); // {"attendance": [{"2025-09-01": 1}], "total": 3, "amount": 2500}
            $table->json('payment_data')->nullable(); // {"amount": 12000, "food_allowance": 3200, "expenses": 1800, "accommodation_allowance": 5600, "hold_for_weeks": 8, "net_amount": 17999}
            $table->json('coordinator_details')->nullable(); // {"coordinator_id": "C102", "current_coordinator": "John Doe", "amount": 2000}
            $table->unsignedBigInteger('job_id');
            $table->string('sheet_no');
            $table->timestamps();

            $table->foreign('position_id')->references('id')->on('promoter_positions')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('custom_jobs')->onDelete('cascade');
            $table->foreign('sheet_no')->references('sheet_no')->on('salary_sheet')->onDelete('cascade');
            
            $table->index(['job_id', 'sheet_no']);
            $table->index(['position_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers_salary_sheet_item');
    }
};