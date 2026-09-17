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
        Schema::create('bk_prtb_educ_grp', function (Blueprint $table) {
            $table->comment('Справочник типов обучения - новый');
            $table->integer('id')->comment('Идентификатор');
            $table->integer('id_bk')->nullable()->comment('Идентификатор в босс-кадровике');
            $table->string('name', 150)->nullable()->comment('Название типа обучения');
            $table->string('id_ebds')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_prtb_educ_grp');
    }
};
