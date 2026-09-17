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
        Schema::create('uc_ext_contractor', function (Blueprint $table) {
            $table->string('id_contractor', 36)->nullable();
            $table->string('namecontractor');
            $table->string('inn', 12)->nullable();
            $table->string('kpp', 9)->nullable();
            $table->string('note', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_contractor');
    }
};
