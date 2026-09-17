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
        Schema::create('srv_demandhistory', function (Blueprint $table) {
            $table->comment('История изменений заявок');
            $table->integer('idrecord');
            $table->integer('iddemand');
            $table->integer('iduser');
            $table->timestamp('timeaction')->comment('Время события');
            $table->integer('typeaction')->comment('Тип события: 1-изменение данных, 2-изменение статуса');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srv_demandhistory');
    }
};
