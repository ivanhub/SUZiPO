<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reasons_non_certification', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); 
        });

        DB::table('reasons_non_certification')->insert([
            ['id' => 1, 'name' => 'Болезнь'],
            ['id' => 2, 'name' => 'Неявка'],
            ['id' => 3, 'name' => 'Уволен'],
            ['id' => 4, 'name' => 'Отпуск'],
            ['id' => 5, 'name' => 'Командировка'],
            ['id' => 6, 'name' => 'Не сдал экзамен'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('reasons_non_certification');
    }
};
