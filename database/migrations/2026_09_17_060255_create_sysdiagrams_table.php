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
        Schema::create('sysdiagrams', function (Blueprint $table) {
            $table->string('name', 128)->nullable();
            $table->integer('principal_id');
            $table->integer('diagram_id');
            $table->integer('version')->nullable();
            $table->binary('definition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sysdiagrams');
    }
};
