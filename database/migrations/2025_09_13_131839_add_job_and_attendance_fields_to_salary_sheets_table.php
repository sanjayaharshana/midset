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
        Schema::table('salary_sheets', function (Blueprint $table) {
            // Job and Location fields
            $table->unsignedBigInteger('job_id')->nullable()->after('coordinator_id');
            $table->string('location')->nullable()->after('job_id');
            
            // Attendance tracking (JSON field to store daily attendance)
            $table->json('attendance_data')->nullable()->after('location');
            
            // Payment breakdown fields
            $table->decimal('food_allowance', 10, 2)->default(0)->after('overtime');
            $table->decimal('expenses', 10, 2)->default(0)->after('food_allowance');
            $table->decimal('accommodation_allowance', 10, 2)->default(0)->after('expenses');
            $table->decimal('hold_for_8_weeks', 10, 2)->default(0)->after('accommodation_allowance');
            
            // Coordinator details
            $table->unsignedBigInteger('current_coordinator_id')->nullable()->after('hold_for_8_weeks');
            $table->decimal('coordination_fee', 10, 2)->default(0)->after('current_coordinator_id');
            
            // Add foreign key for job
            $table->foreign('job_id')->references('id')->on('custom_jobs')->onDelete('set null');
            $table->foreign('current_coordinator_id')->references('id')->on('coordinators')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the table exists before trying to modify it
        if (Schema::hasTable('salary_sheets')) {
            Schema::table('salary_sheets', function (Blueprint $table) {
                // Check if foreign keys exist before dropping them
                if (Schema::hasColumn('salary_sheets', 'job_id')) {
                    $table->dropForeign(['job_id']);
                }
                if (Schema::hasColumn('salary_sheets', 'current_coordinator_id')) {
                    $table->dropForeign(['current_coordinator_id']);
                }
                
                $columnsToDrop = [];
                $columns = [
                    'job_id',
                    'location',
                    'attendance_data',
                    'food_allowance',
                    'expenses',
                    'accommodation_allowance',
                    'hold_for_8_weeks',
                    'current_coordinator_id',
                    'coordination_fee'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('salary_sheets', $column)) {
                        $columnsToDrop[] = $column;
                    }
                }
                
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};