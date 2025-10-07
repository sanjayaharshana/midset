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
        Schema::table('clients', function (Blueprint $table) {
            // Drop unique constraints
            $table->dropUnique('clients_email_unique');
            $table->dropUnique('clients_short_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Re-add unique constraints if needed
            $table->unique('email', 'clients_email_unique');
            $table->unique('short_code', 'clients_short_code_unique');
        });
    }
};
