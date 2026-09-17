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
        Schema::create('bk_depart_', function (Blueprint $table) {
            $table->comment('Справочник подразделений');
            $table->integer('id')->comment('Идентификатор');
            $table->integer('id_bk')->nullable()->comment('Идентификатор в БОСС-кадровике');
            $table->string('departtmentname')->nullable()->comment('Наименование подразделения');
            $table->integer('parent_id_bk')->nullable()->comment('Ссылка на родительское подразделение');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_depart_');
    }
};
