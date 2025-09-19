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
        Schema::table('employers_salary_sheet_item', function (Blueprint $table) {
            $table->unsignedBigInteger('promoter_id')->nullable()->after('position_id');
            $table->foreign('promoter_id')->references('id')->on('promoters')->onDelete('cascade');
            $table->index(['promoter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employers_salary_sheet_item', function (Blueprint $table) {
            $table->dropForeign(['promoter_id']);
            $table->dropIndex(['promoter_id']);
            $table->dropColumn('promoter_id');
        });
    }
};
