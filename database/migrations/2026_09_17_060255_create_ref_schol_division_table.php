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
        Schema::create('ref_schol_division', function (Blueprint $table) {
            $table->comment('Справочник учебных отделов');
            $table->integer('id')->comment('Идентификатор учебного отдела');
            $table->string('name', 50)->nullable()->comment('Название учебного отдела');
            $table->string('email', 50)->nullable()->comment('Оффициальный почтовый адрес для оповещений');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_schol_division');
    }
};
