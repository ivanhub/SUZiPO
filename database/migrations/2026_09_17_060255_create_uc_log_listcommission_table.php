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
        Schema::create('uc_log_listcommission', function (Blueprint $table) {
            $table->string('id_log_listcommission', 36)->nullable();
            $table->string('id_listcommission', 36)->nullable();
            $table->string('fio')->nullable();
            $table->string('staff')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('note')->nullable();
            $table->timestamp('datechange');
            $table->string('Login', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_log_listcommission');
    }
};
