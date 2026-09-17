<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            $table->text('absence_reason')->nullable()->after('absence_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            $table->dropColumn('absence_reason');
        });
    }
};