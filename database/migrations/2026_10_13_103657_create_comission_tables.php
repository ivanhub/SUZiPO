<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comission_members', function (Blueprint $table) {
            $table->id();
            $table->string('fio', 255);
            $table->string('position', 255);
        });

        // Таблица связи протокола с выбранными членами комиссии
        Schema::create('protocol_comission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protocol_id'); // ID протокола (prot_id)
            $table->unsignedBigInteger('member_id');   // ID сотрудника из справочника
            $table->string('role', 50); // chairman (председатель), deputy (зам), member (член)
        });

        // TEST DATA
        DB::table('comission_members')->insert([
            ['id' => 1, 'fio' => 'Фасхутдинов А.М.', 'position' => 'Начальник Учебного центра'],
            ['id' => 2, 'fio' => 'Чухарев К.А.', 'position' => 'И.о. начальника методического отдела'],
            ['id' => 3, 'fio' => 'Юсупов И.З.', 'position' => 'Ведущий специалист по охране труда и пожарной безопасности'],
            ['id' => 4, 'fio' => 'Худайдатов Р.Р.', 'position' => 'Ведущий специалист по охране труда и промышленной безопасности'],
            ['id' => 5, 'fio' => 'Хайруллин Е.А.', 'position' => 'Старший мастер ЦЗОТВС-1'],
            ['id' => 6, 'fio' => 'Сидоров С.В.', 'position' => 'Начальник Цеха'],
            ['id' => 7, 'fio' => 'Кузнецова М.М.', 'position' => 'Специалист по обучению'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('protocol_comission');
        Schema::dropIfExists('comission_members');
    }
};
