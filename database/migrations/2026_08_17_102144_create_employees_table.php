<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('sap_id', 50)->unique(); // Идентификатор из SAP
            $table->string('full_name');
            $table->foreignId('profession_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('qualification_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};