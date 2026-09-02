<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests_cities', function (Blueprint $table) {
            $table->integer('option_value')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('requests_cities', function (Blueprint $table) {
            $table->dropColumn('option_value');
        });
    }
};