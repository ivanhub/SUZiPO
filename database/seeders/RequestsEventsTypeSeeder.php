<?php

namespace Database\Seeders;

use App\Models\RequestsEventsType;
use Illuminate\Database\Seeder;

class RequestsEventsTypeSeeder extends Seeder
{
    public function run(): void
    {
        $eventsTypes = [
            ['name' => '------------------'],
            ['name' => 'Дистанционное обучение'],
            ['name' => 'Конференция, форум'],
            ['name' => 'Курс обучения'],
            ['name' => 'Оценка корпоративных, управленческих компетенций'],
            ['name' => 'Оценка профессионально-технических компетенций'],
            ['name' => 'Программы дополнительного образования (повышение квалификации, профессиональная переподготовка)'],
            ['name' => 'Программы профессионального образования'],
            ['name' => 'Программы профессионального обучения'],
        ];

        foreach ($eventsTypes as $eventsType) {
            RequestsEventsType::create($eventsType);
        }

        $this->command->info('Типы мероприятий успешно загружены: ' . count($eventsTypes) . ' записей');
    }
}