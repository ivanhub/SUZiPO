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
        Schema::create('uc_ext_teacher', function (Blueprint $table) {
            $table->string('id_teacher', 36)->nullable();
            $table->string('fio_teacher')->nullable();
            $table->string('staff_teacher')->nullable();
            $table->string('typeofwork', 50)->nullable();
            $table->string('status', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_teacher');
    }
};
