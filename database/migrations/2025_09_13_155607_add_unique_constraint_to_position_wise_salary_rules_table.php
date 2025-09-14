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
        // Use raw SQL to handle this more safely
        // Check if job_id column exists first
        $columns = DB::select("SHOW COLUMNS FROM position_wise_salary_rules LIKE 'job_id'");
        if (empty($columns)) {
            DB::statement('ALTER TABLE position_wise_salary_rules ADD COLUMN job_id BIGINT UNSIGNED NULL AFTER position_id');
        }
        
        // Drop foreign key constraint if it exists
        try {
            DB::statement('ALTER TABLE position_wise_salary_rules DROP FOREIGN KEY position_wise_salary_rules_position_id_foreign');
        } catch (\Exception $e) {
            // Try alternative constraint name
            try {
                DB::statement('ALTER TABLE position_wise_salary_rules DROP FOREIGN KEY position_wise_salary_rules_position_id_foreign');
            } catch (\Exception $e2) {
                // Constraint doesn't exist, continue
            }
        }
        
        // Drop unique constraint if it exists
        try {
            DB::statement('ALTER TABLE position_wise_salary_rules DROP INDEX position_wise_salary_rules_position_id_unique');
        } catch (\Exception $e) {
            // Try alternative constraint name
            try {
                DB::statement('ALTER TABLE position_wise_salary_rules DROP INDEX position_id');
            } catch (\Exception $e2) {
                // Constraint doesn't exist, continue
            }
        }
        
        // Add composite unique constraint
        DB::statement('ALTER TABLE position_wise_salary_rules ADD UNIQUE position_job_unique (position_id, job_id)');
        
        // Add foreign key constraints
        DB::statement('ALTER TABLE position_wise_salary_rules ADD CONSTRAINT position_wise_salary_rules_position_id_foreign FOREIGN KEY (position_id) REFERENCES promoter_positions(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE position_wise_salary_rules ADD CONSTRAINT position_wise_salary_rules_job_id_foreign FOREIGN KEY (job_id) REFERENCES custom_jobs(id) ON DELETE CASCADE');
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