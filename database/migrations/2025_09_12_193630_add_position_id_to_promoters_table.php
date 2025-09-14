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
        Schema::table('promoters', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable()->after('promoter_id');
            $table->foreign('position_id')->references('id')->on('promoter_positions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promoters', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
};