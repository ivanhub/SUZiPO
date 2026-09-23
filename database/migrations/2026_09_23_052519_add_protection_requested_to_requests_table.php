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
    Schema::table('requests', function (Blueprint $table) {
        $table->boolean('protection_requested')->default(false)->after('req_prefix');
    });
}

public function down(): void
{
    Schema::table('requests', function (Blueprint $table) {
        $table->dropColumn('protection_requested');
    });
}
};
