<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses_urp', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->text('name');
            $table->timestamps();
            
            // Индекс для поиска
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses_urp');
    }
};