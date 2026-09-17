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
        Schema::create('uc_ext_staffdict', function (Blueprint $table) {
            $table->string('id_categorystaff', 36)->nullable();
            $table->text('staff');
            $table->string('category')->nullable();
            $table->string('note', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_staffdict');
    }
};
