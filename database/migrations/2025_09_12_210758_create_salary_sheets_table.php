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
        Schema::create('salary_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_number')->unique();
            $table->string('month');
            $table->integer('year');
            $table->unsignedBigInteger('promoter_id')->nullable();
            $table->unsignedBigInteger('coordinator_id')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('bonuses', 10, 2)->default(0);
            $table->decimal('overtime', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('promoter_id')->references('id')->on('promoters')->onDelete('cascade');
            $table->foreign('coordinator_id')->references('id')->on('coordinators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_sheets');
    }
};