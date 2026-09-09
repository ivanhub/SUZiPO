<?php
namespace Database\Factories;

use App\Models\Protocol;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProtocolFactory extends Factory
{
    protected $model = Protocol::class;

    public function definition(): array
    {
        // Устанавливаем русскую локаль для Faker локально, если не настроено глобально
        $this->faker = \Faker\Factory::create('ru_RU');

        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = (clone $startDate)->modify('+' . rand(30, 90) . ' days'); 

        return [
            'status' => $this->faker->randomElement(['Архив', 'Черновик', 'Завершен', 'Отменен', 'Просрочен']),
            'registration_number' => $this->faker->unique()->numberBetween(1, 9999),
            'creator_name' => $this->faker->name(),
            'course_title' => $this->faker->randomElement([
                'Разработка на PHP и Laravel', 'Администрирование PostgreSQL', 
                'Frontend: Vue.js', 'Системный анализ', 'Data Science и Python'
            ]),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'editor_name' => $this->faker->optional(0.7)->name(), 
            'application_number' => $this->faker->numberBetween(100, 999),
            'application_date' => $this->faker->dateTimeBetween('-2 months', '-1 month')->format('Y-m-d'),
            'qualification' => $this->faker->randomElement(['4 разряд', '5 разряд', '6 разряд', '7 разряд']),
            'teacher_fio' => $this->faker->name(),
            'classroom_number' => 'Ауд. ' . $this->faker->numberBetween(101, 505),
            'group_curator' => $this->faker->name(),
        ];
    }
}
