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
        Schema::create('bk_prof', function (Blueprint $table) {
            $table->comment('Справочник (Ref) профессий');
            $table->integer('id')->comment('Идентификатор профессии');
            $table->integer('id_bk')->nullable()->comment('Идентификатор босс-кадровика');
            $table->string('name')->nullable()->comment('Наименование проффессии');
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
        Schema::dropIfExists('bk_prof');
    }
};
