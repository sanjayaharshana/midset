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
            $table->decimal('default_coordinator_fee', 10, 2)->nullable()->after('end_date');
            $table->decimal('default_hold_for_8_weeks', 10, 2)->nullable()->after('default_coordinator_fee');
            $table->decimal('default_food_allowance', 10, 2)->nullable()->after('default_hold_for_8_weeks');
            $table->decimal('default_accommodation_allowance', 10, 2)->nullable()->after('default_food_allowance');
            $table->decimal('default_expenses', 10, 2)->nullable()->after('default_accommodation_allowance');
            $table->string('default_location')->nullable()->after('default_expenses');
            $table->text('location_notes')->nullable()->after('default_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'default_coordinator_fee',
                'default_hold_for_8_weeks',
                'default_food_allowance',
                'default_accommodation_allowance',
                'default_expenses',
                'default_location',
                'location_notes'
            ]);
        });
    }
};
