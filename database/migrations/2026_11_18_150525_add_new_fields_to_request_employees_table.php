<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            // Для ручного ввода
            $table->string('department', 255)->nullable()->after('middle_name'); // Структурное подразделение
            $table->string('citizenship', 100)->nullable()->after('pfr_certificate'); // Гражданство
        });
    }

    public function down(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            $table->dropColumn(['department', 'citizenship']);
        });
    }
};