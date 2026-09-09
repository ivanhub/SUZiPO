<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'request_id')) {
                $table->foreignId('request_id')
                      ->nullable()
                      ->constrained('requests')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn('request_id');
        });
    }
};