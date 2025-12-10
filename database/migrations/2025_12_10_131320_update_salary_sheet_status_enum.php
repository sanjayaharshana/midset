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
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support ENUM, so we need to recreate the table
            // First, create a new table with updated schema
            Schema::create('salary_sheet_new', function (Blueprint $table) {
                $table->id();
                $table->string('sheet_no')->unique();
                $table->unsignedBigInteger('job_id');
                $table->string('status')->default('draft'); // Use string instead of enum for SQLite
                $table->string('location')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('job_id')->references('id')->on('custom_jobs')->onDelete('cascade');
                $table->index(['job_id', 'status']);
            });
            
            // Copy data from old table to new table
            // Map old status values to new ones
            DB::statement("
                INSERT INTO salary_sheet_new (id, sheet_no, job_id, status, location, notes, created_at, updated_at)
                SELECT id, sheet_no, job_id, 
                       CASE 
                           WHEN status = 'approved' THEN 'complete'
                           ELSE status
                       END as status,
                       location, notes, created_at, updated_at
                FROM salary_sheet
            ");
            
            // Drop old table
            Schema::dropIfExists('salary_sheet');
            
            // Rename new table
            Schema::rename('salary_sheet_new', 'salary_sheet');
        } else {
            // MySQL/MariaDB - use ALTER TABLE
            DB::statement("ALTER TABLE `salary_sheet` MODIFY COLUMN `status` ENUM('draft', 'complete', 'reject', 'paid') NOT NULL DEFAULT 'draft'");
            
            // Update existing 'approved' status to 'complete'
            DB::statement("UPDATE `salary_sheet` SET `status` = 'complete' WHERE `status` = 'approved'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // Revert SQLite changes
            Schema::create('salary_sheet_old', function (Blueprint $table) {
                $table->id();
                $table->string('sheet_no')->unique();
                $table->unsignedBigInteger('job_id');
                $table->string('status')->default('draft');
                $table->string('location')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('job_id')->references('id')->on('custom_jobs')->onDelete('cascade');
                $table->index(['job_id', 'status']);
            });
            
            // Copy data back, mapping 'complete' to 'approved'
            DB::statement("
                INSERT INTO salary_sheet_old (id, sheet_no, job_id, status, location, notes, created_at, updated_at)
                SELECT id, sheet_no, job_id, 
                       CASE 
                           WHEN status = 'complete' THEN 'approved'
                           WHEN status = 'reject' THEN 'draft'
                           ELSE status
                       END as status,
                       location, notes, created_at, updated_at
                FROM salary_sheet
            ");
            
            Schema::dropIfExists('salary_sheet');
            Schema::rename('salary_sheet_old', 'salary_sheet');
        } else {
            // Revert MySQL changes
            DB::statement("UPDATE `salary_sheet` SET `status` = 'approved' WHERE `status` = 'complete'");
            DB::statement("UPDATE `salary_sheet` SET `status` = 'draft' WHERE `status` = 'reject'");
            DB::statement("ALTER TABLE `salary_sheet` MODIFY COLUMN `status` ENUM('draft', 'approved', 'paid') NOT NULL DEFAULT 'draft'");
        }
    }
};
