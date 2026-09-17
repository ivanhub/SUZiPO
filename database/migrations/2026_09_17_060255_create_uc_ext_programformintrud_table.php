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
        Schema::create('uc_ext_programformintrud', function (Blueprint $table) {
            $table->string('id_programformintrud', 36)->nullable();
            $table->string('codeot', 50)->nullable();
            $table->string('idprogram', 50)->nullable();
            $table->text('nameprogram')->nullable();
            $table->string('status', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_programformintrud');
    }
};
