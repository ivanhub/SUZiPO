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
        Schema::create('bk_appointment_', function (Blueprint $table) {
            $table->comment('Таблица должностей');
            $table->integer('id')->comment('Идентификатор');
            $table->integer('id_bk')->comment('Идентификатор в босс-кадровике');
            $table->string('name')->nullable()->comment('Наименование должности');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_appointment_');
    }
};
