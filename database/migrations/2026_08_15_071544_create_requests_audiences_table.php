<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests_audiences', function (Blueprint $table) {
            $table->id();
            $table->string('number', 100);
            $table->string('location', 500)->nullable();
            $table->string('responsible_person', 500)->nullable();
            $table->string('seats', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests_audiences');
    }
};