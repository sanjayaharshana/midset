<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('position_wise_salary_rules', function (Blueprint $table) {
            // Add job_id column if it doesn't exist
            if (!Schema::hasColumn('position_wise_salary_rules', 'job_id')) {
                $table->unsignedBigInteger('job_id')->nullable()->after('position_id');
            }
            
            // Drop existing foreign key constraints and unique constraints
            $table->dropForeign(['position_id']);
            $table->dropUnique(['position_id']);
            
            // Add composite unique constraint
            $table->unique(['position_id', 'job_id'], 'position_job_unique');
            
            // Add foreign key constraints
            $table->foreign('position_id')->references('id')->on('promoter_positions')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('custom_jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('position_wise_salary_rules', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['position_id']);
            $table->dropForeign(['job_id']);
            
            // Drop the composite unique constraint
            $table->dropUnique('position_job_unique');
            
            // Drop the job_id column
            $table->dropColumn('job_id');
            
            // Restore the original unique constraint on position_id
            $table->unique('position_id');
            
            // Restore the foreign key constraint for position_id
            $table->foreign('position_id')->references('id')->on('promoter_positions')->onDelete('cascade');
        });
    }
};