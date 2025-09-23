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
        Schema::table('custom_jobs', function (Blueprint $table) {
            $table->foreignId('officer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('reporter_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_jobs', function (Blueprint $table) {
            $table->dropForeign(['officer_id']);
            $table->dropForeign(['reporter_id']);
            $table->dropColumn(['officer_id', 'reporter_id']);
        });
    }
};