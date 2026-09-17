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
        Schema::create('uc_log_actstudentpr', function (Blueprint $table) {
            $table->string('id_log_actstudentspr', 36)->nullable();
            $table->string('id_actstudentspr', 36)->nullable();
            $table->string('id_actissuance', 36)->nullable();
            $table->string('id_studentprotocol', 36)->nullable();
            $table->string('note')->nullable();
            $table->timestamp('datechange')->nullable();
            $table->string('Login', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_log_actstudentpr');
    }
};
