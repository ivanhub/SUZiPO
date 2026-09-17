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
        Schema::create('uc_ext_programecp', function (Blueprint $table) {
            $table->string('id_programecp', 36)->nullable();
            $table->text('nameprogram')->nullable();
            $table->string('counthour')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('Login', 100)->nullable();
            $table->date('dateend')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_programecp');
    }
};
