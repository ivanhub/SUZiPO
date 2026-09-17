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
        Schema::create('bk_edtype', function (Blueprint $table) {
            $table->comment('Справочник типов обучения (выставки, вечернее, и прочая ахинея)');
            $table->integer('id')->comment('Идентификатор');
            $table->integer('id_bk')->nullable()->comment('Идентификатор в босс-кадровике');
            $table->string('name', 150)->nullable()->comment('Название типа обучения');
            $table->string('id_ebds')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->integer('isactive');
            $table->string('usercreate', 50)->nullable();
            $table->string('useredit', 50)->nullable();
            $table->timestamp('editdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_edtype');
    }
};
