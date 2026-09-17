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
        Schema::create('uc_ext_typeofeducprogram', function (Blueprint $table) {
            $table->string('id_typeofeducprogram', 36)->nullable();
            $table->string('id_typeofeduc', 36)->nullable();
            $table->string('id_document', 36)->nullable();
            $table->string('nameprogram')->nullable();
            $table->float('counthour')->nullable();
            $table->float('counthourtheory')->nullable();
            $table->float('counthourself')->nullable();
            $table->float('counthourprodpractic')->nullable();
            $table->float('counthourpractic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_typeofeducprogram');
    }
};
