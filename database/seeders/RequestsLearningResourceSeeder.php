<?php

namespace Database\Seeders;

use App\Models\RequestsLearningResource;
use Illuminate\Database\Seeder;

class RequestsLearningResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            ['name' => 'ВНЕШНИЕ ПРОВАЙДЕРЫ'],
            ['name' => 'ВНУТРЕННИЕ ТРЕНЕРЫ ОГ'],
            ['name' => 'ВНУТРЕННИЕ ТРЕНЕРЫ ЦАУК'],
            ['name' => 'Корпоративная система дистанционного обучения'],
            ['name' => 'КОРПОРАТИВНЫЕ УЧЕБНЫЕ ЦЕНТРЫ (В СТРУКТУРЕ ОГ И ПЕРИМЕТРЕ КОМПАНИИ)'],
        ];

        foreach ($resources as $resource) {
            RequestsLearningResource::create($resource);
        }

        $this->command->info('Ресурсы обучения успешно загружены: ' . count($resources) . ' записей');
    }
}