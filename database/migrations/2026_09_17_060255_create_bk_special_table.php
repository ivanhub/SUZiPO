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
        Schema::create('bk_special', function (Blueprint $table) {
            $table->comment('Справочник (Ref) специальностей');
            $table->integer('id')->comment('Идентификатор специальности');
            $table->integer('id_bk')->nullable()->comment('Идентификатор БК');
            $table->string('name')->nullable()->comment('Название специальности');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_special');
    }
};
