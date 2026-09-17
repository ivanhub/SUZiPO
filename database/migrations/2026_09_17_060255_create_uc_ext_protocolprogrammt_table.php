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
        Schema::create('uc_ext_protocolprogrammt', function (Blueprint $table) {
            $table->string('id_protocolprogrammt', 36)->nullable();
            $table->string('id_protocol', 36)->nullable();
            $table->string('id_programformintrud', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_protocolprogrammt');
    }
};
