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
        Schema::create('dtproperties', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('objectid')->nullable();
            $table->string('property', 64)->nullable();
            $table->string('value')->nullable();
            $table->string('uvalue')->nullable();
            $table->binary('lvalue')->nullable();
            $table->integer('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtproperties');
    }
};
