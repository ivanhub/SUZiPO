<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('fio', 500);
            $table->string('profession', 500)->nullable();
            $table->string('division1', 500)->nullable();
            $table->string('division2', 500)->nullable();
            $table->string('division3', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests_teachers');
    }
};