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
        Schema::create('bk_qual', function (Blueprint $table) {
            $table->comment('Справочник (Ref) квалификаций');
            $table->integer('id')->comment('Идентификатор квалификации');
            $table->integer('id_bk')->nullable()->comment('Идентификатор из босс-кадровика');
            $table->string('name')->nullable()->comment('Название квалификации');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_qual');
    }
};
