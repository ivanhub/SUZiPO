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
        Schema::create('app_commission', function (Blueprint $table) {
            $table->comment('Члены  аттестационной комиссии');
            $table->integer('prot_id')->comment('Идентификатор протокола');
            $table->integer('id');
            $table->string('fiomember', 100)->nullable()->comment('ФИО члена комисси');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_commission');
    }
};
