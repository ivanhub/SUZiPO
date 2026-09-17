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
        Schema::create('all_users_sap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tab_number')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('gender_key', 20)->nullable();
            $table->string('pfr_certificate', 50)->nullable();
            $table->string('position')->nullable();
            $table->string('rank', 50)->nullable();
            $table->string('level_4_name', 500)->nullable();
            $table->string('level_3_name', 500)->nullable();
            $table->string('duv_b', 50)->nullable();
            $table->string('mvz', 50)->nullable();
            $table->string('employee_category', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_users_sap');
    }
};
