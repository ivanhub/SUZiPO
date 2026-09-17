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
        Schema::create('ref_reason', function (Blueprint $table) {
            $table->comment('Таблица - справочник причин (Reason) не проведений (обучений?)');
            $table->integer('id')->comment('Идентификатор');
            $table->string('name', 50)->nullable()->comment('Наименование причины не проведения');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_reason');
    }
};
