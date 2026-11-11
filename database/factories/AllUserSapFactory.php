<?php

namespace Database\Factories;

use App\Models\AllUserSap;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllUserSapFactory extends Factory
{
    private static $tabNumber = 10000000;

    public function definition(): array
    {
        $genderKey = $this->faker->randomElement(['1', '2']);
        $genderText = $genderKey === '1' ? 'мужской' : 'женский';

        if ($genderText === 'мужской') {
            $lastName = $this->faker->lastName('male');
            $firstName = $this->faker->firstName('male');
            $middleName = $this->faker->middleName('male');
        } else {
            $lastName = $this->faker->lastName('female');
            $firstName = $this->faker->firstName('female');
            $middleName = $this->faker->middleName('female');
        }

        return [
            'tab_number'        => self::$tabNumber++, 
            'last_name'         => $lastName,
            'first_name'        => $firstName,
            'middle_name'       => $middleName,
            'birth_date'        => $this->faker->date('d.m.Y', '-20 years'),
            'gender'            => $genderText,
            'gender_key'        => $genderKey,
            'pfr_certificate'   => $this->faker->unique()->numerify('###-###-## ##'),
            'position'          => $this->faker->randomElement(['Главный специалист', 'Оператор товарный', 'Инженер', 'Ведущий технолог', 'Ведущий специалист']),
            'rank'              => $this->faker->randomElement(['5', '6', '7']),
            'level_4_name'      => $this->faker->randomElement(['Отдел производственного контроля и пром.', 'Цех подготовки и перекачки нефти №1']),
            'level_3_name'      => $this->faker->randomElement(['Управление промышленной безопасности и охраны', 'Управление подготовки и перекачки нефти']),
            'duv_b'             => \Illuminate\Support\Carbon::create(9999, 12, 31),
            'mvz'               => $this->faker->unique()->numerify('##########'),
            'employee_category' => $this->faker->randomElement(['СпециалистыОкл', 'РабочиеЧТССумм']),
        ];
    }
}

