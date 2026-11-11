<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_of_education', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
        });

        DB::table('type_of_education')->insert([
            ['id' => 1, 'name' => 'ДПО'],
            ['id' => 2, 'name' => 'ПО'],
            ['id' => 3, 'name' => 'курсы'],
            ['id' => 4, 'name' => 'ОТ'],
            ['id' => 5, 'name' => 'Сторонние УЦ'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('type_of_education');
    }
};
