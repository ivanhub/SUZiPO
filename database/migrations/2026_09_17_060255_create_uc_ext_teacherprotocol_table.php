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
        Schema::create('uc_ext_teacherprotocol', function (Blueprint $table) {
            $table->string('id_teacherprotocol', 36)->nullable();
            $table->string('id_teacher', 36)->nullable();
            $table->string('id_protocol', 36)->nullable();
            $table->string('fiofact')->nullable();
            $table->string('stafffact')->nullable();
            $table->string('typeofwork', 50)->nullable();
            $table->string('raiting', 50)->nullable();
            $table->string('note', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_teacherprotocol');
    }
};
