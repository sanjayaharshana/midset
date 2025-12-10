<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Since we're using SQLite (string type), no migration needed
        // The status column already accepts any string value
        // This migration is kept for consistency if switching to MySQL later
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for SQLite
    }
};
