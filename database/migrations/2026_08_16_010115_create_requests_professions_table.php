<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests_professions', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->nullable();
            $table->string('name', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests_professions');
    }
};