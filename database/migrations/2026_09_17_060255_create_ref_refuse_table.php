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
        Schema::create('ref_refuse', function (Blueprint $table) {
            $table->comment('Справочник причин отказов от выполнения заявокм (отсутствие преподавателя, )');
            $table->integer('id')->comment('Идентификатор');
            $table->string('name', 50)->nullable()->comment('Текст причины отказа');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_refuse');
    }
};
