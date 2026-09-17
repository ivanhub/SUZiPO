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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign(['audience_id'])->references(['id'])->on('requests_audiences')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['request_id'])->references(['id'])->on('requests')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['teacher_id'])->references(['id'])->on('requests_teachers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_audience_id_foreign');
            $table->dropForeign('bookings_request_id_foreign');
            $table->dropForeign('bookings_teacher_id_foreign');
        });
    }
};
