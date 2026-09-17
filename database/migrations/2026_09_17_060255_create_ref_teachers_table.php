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
        Schema::create('ref_teachers', function (Blueprint $table) {
            $table->integer('id');
            $table->string('fio', 100)->nullable();
            $table->string('professionorpost', 100)->nullable();
            $table->string('subdivision1', 100)->nullable();
            $table->string('subdivision2', 100)->nullable();
            $table->string('subdivision3', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_teachers');
    }
};
