<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->date('valid_from')->default('1800-01-01'); // Начало
            $table->date('valid_to')->default('9999-12-31');   // Истечение
            $table->string('sap_code', 10)->nullable();        // Код SAP
            $table->string('name', 255);                       // Тип документа
            $table->string('print_code', 10)->nullable();      // Код документа на печать
            $table->timestamp('changed_at')->nullable();       // Дата изменения
            $table->string('changed_by', 255)->nullable();     // Изменил
        });

        DB::table('document_types')->insert([
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '09', 'name' => 'Свидетельство', 'print_code' => '02', 'changed_at' => null, 'changed_by' => null],
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '50', 'name' => 'Диплом',        'print_code' => '03', 'changed_at' => null, 'changed_by' => null],
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '60', 'name' => 'Сертификат',    'print_code' => '04', 'changed_at' => null, 'changed_by' => null],
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '65', 'name' => 'Удостоверение', 'print_code' => '01', 'changed_at' => null, 'changed_by' => null],
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '70', 'name' => 'Аттестат',     'print_code' => null, 'changed_at' => null, 'changed_by' => null],
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '94', 'name' => 'Справка',      'print_code' => '05', 'changed_at' => null, 'changed_by' => null],
            ['valid_from' => '1800-01-01', 'valid_to' => '9999-12-31', 'sap_code' => '95', 'name' => 'Протокол',     'print_code' => null, 'changed_at' => null, 'changed_by' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
