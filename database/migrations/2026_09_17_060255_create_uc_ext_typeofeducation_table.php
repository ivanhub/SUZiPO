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
        Schema::create('uc_ext_typeofeducation', function (Blueprint $table) {
            $table->string('id_typeofeducation', 36)->nullable();
            $table->string('typeeduc', 50)->nullable();
            $table->integer('codetype')->nullable();
            $table->string('status', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_typeofeducation');
    }
};
