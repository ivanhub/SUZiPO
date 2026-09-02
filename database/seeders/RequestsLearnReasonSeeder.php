<?php

namespace Database\Seeders;

use App\Models\RequestsLearnReason;
use Illuminate\Database\Seeder;

class RequestsLearnReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            ['name' => 'Аттестация'],
            ['name' => 'Модульное обучение'],
            ['name' => 'Оценка компетенций'],
            ['name' => 'Подготовка к смотрам/конкурсам'],
            ['name' => 'Разовое обучение'],
        ];

        foreach ($reasons as $reason) {
            RequestsLearnReason::create($reason);
        }

        $this->command->info('Причины обучения успешно загружены: ' . count($reasons) . ' записей');
    }
}