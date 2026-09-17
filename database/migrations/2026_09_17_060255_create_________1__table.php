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
        Schema::create('Лист1$', function (Blueprint $table) {
            $table->string('id_teacher')->nullable();
            $table->string('fio_teacher')->nullable();
            $table->string('numberprotocol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Лист1$');
    }
};
