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
        Schema::create('srv_listsending', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('typenotifi')->nullable();
            $table->string('listsending', 250)->nullable();
            $table->string('listsendingof', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srv_listsending');
    }
};
