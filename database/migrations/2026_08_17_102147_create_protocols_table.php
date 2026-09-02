<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('protocol_number', 100)->nullable();
            $table->date('date')->nullable();
            $table->string('status', 50)->default('draft');
            $table->string('result', 255)->nullable();
            $table->string('file_path', 500)->nullable();
            $table->timestamps();
            
            // Индексы
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocols');
    }
};