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
        Schema::create('request_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('request_id')->index();
            $table->bigInteger('user_sap_id')->nullable()->index();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('absence_start_date')->nullable();
            $table->date('absence_end_date')->nullable();
            $table->string('absence_type', 50)->nullable();
            $table->date('distance_learning_date')->nullable();
            $table->date('fulltime_learning_date')->nullable();
            $table->text('note')->nullable();
            $table->date('document_issue_date')->nullable();
            $table->string('reissue_period', 50)->nullable();
            $table->string('status', 20)->default('active');
            $table->string('warning_type', 50)->nullable();
            $table->string('warning_message', 500)->nullable();
            $table->timestamps();
            $table->string('tab_number', 50)->nullable()->index();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_employees');
    }
};
