<?php

namespace Database\Seeders;

use App\Models\RequestsLearningType;
use Illuminate\Database\Seeder;

class RequestsLearningTypeSeeder extends Seeder
{
    public function run(): void
    {
        $learningTypes = [
            ['name' => '------------------'],
            ['name' => 'АДРЕСНОЕ обучение (профессионально-техническое и управленческое)'],
            ['name' => 'КОРПОРАТИВНОЕ профессионально-техническое обучение'],
            ['name' => 'КОРПОРАТИВНОЕ управленческое обучение'],
            ['name' => 'Обучение иностранным языкам'],
            ['name' => 'Обязательное обучение'],
            ['name' => 'Оценка при планировании обучения и развития'],
            ['name' => 'Оценка при приеме на работу и перемещение в должности'],
            ['name' => 'Оценка при формировании Кадрового резерва и экспертных сообществ'],
        ];

        foreach ($learningTypes as $learningType) {
            RequestsLearningType::create($learningType);
        }

        $this->command->info('Типы обучения успешно загружены: ' . count($learningTypes) . ' записей');
    }
}