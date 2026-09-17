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
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('audience_id');
            $table->bigInteger('teacher_id');
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->bigInteger('request_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unique(['audience_id', 'start_date']);
            $table->unique(['teacher_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
