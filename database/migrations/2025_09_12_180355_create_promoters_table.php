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
        Schema::create('promoters', function (Blueprint $table) {
            $table->id();
            $table->string('promoter_id')->unique();
            $table->string('promoter_name');
            $table->string('identity_card_no')->unique();
            $table->string('phone_no');
            $table->string('bank_name');
            $table->string('bank_branch_name');
            $table->string('bank_account_number');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoters');
    }
};