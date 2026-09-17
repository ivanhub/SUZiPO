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
        Schema::create('bk_typedoc', function (Blueprint $table) {
            $table->comment('Типы документов об обучении');
            $table->integer('id')->comment('Внутренний идентификатор');
            $table->integer('id_bk')->nullable()->comment('Мдентификатор БК');
            $table->string('name', 100)->nullable()->comment('Название типа документа');
            $table->string('id_ebds')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->string('documentprintcode', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_typedoc');
    }
};
