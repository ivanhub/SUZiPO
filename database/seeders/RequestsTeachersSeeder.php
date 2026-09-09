<?php

namespace Database\Seeders;

use App\Models\RequestsTeachers;
use Illuminate\Database\Seeder;

class RequestsTeachersSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'fio' => 'Галяхметов Игорь Харисович',
                'profession' => 'Ведущий специалист по обучению',
                'division1' => 'Учебный центр',
                'division2' => 'Отдел организации обучения',
                'division3' => null,
            ],
            [
                'fio' => 'Чухарев Константин Александрович',
                'profession' => 'Ведущий специалист по обучению',
                'division1' => 'Учебный центр',
                'division2' => 'Отдел организации обучения',
                'division3' => null,
            ],
            [
                'fio' => 'Головин Александр Александрович',
                'profession' => 'Ведущий специалист по обучению',
                'division1' => 'Учебный центр',
                'division2' => 'Отдел организации обучения',
                'division3' => null,
            ],
            [
                'fio' => 'Фролова Валентина Сергеевна',
                'profession' => 'Ведущий специалист по обучению',
                'division1' => 'Учебный центр',
                'division2' => 'Отдел организации обучения',
                'division3' => null,
            ],
            [
                'fio' => 'Кизьяков Игорь Николаевич',
                'profession' => 'Ведущий специалист по обучению',
                'division1' => 'Учебный центр',
                'division2' => 'Отдел организации обучения',
                'division3' => null,
            ],
            [
                'fio' => 'Голушко Сергей Васильевич',
                'profession' => 'Ведущий специалист по обучению',
                'division1' => 'Учебный центр',
                'division2' => 'Отдел организации обучения',
                'division3' => null,
            ],
            [
                'fio' => 'Лысов Сергей Владимирович',
                'profession' => 'Главный специалист',
                'division1' => 'Учебный центр',
                'division2' => 'Методический отдел',
                'division3' => null,
            ],
            [
                'fio' => 'Репицкий Данил Валерьевич',
                'profession' => 'Механик',
                'division1' => 'Учебный центр',
                'division2' => 'Полигон практического тренинга',
                'division3' => null,
            ],
            [
                'fio' => 'Мазник Эдуард Анатольевич',
                'profession' => 'мастер',
                'division1' => 'АУП ЦТОРТ 3',
                'division2' => '',
                'division3' => '',
            ],
        ];

        foreach ($teachers as $teacher) {
            RequestsTeachers::create($teacher);
        }

        $this->command->info('Преподаватели успешно загружены: ' . count($teachers) . ' записей');
    }
}