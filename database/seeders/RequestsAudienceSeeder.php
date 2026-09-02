<?php

namespace Database\Seeders;

use App\Models\RequestsAudience;
use Illuminate\Database\Seeder;

class RequestsAudienceSeeder extends Seeder
{
    public function run(): void
    {
        $audiences = [
            ['number' => '1102', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '14'],
            ['number' => '1303', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '10'],
            ['number' => '1317', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '25'],
            ['number' => '4102', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '25'],
            ['number' => '4202', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '50'],
            ['number' => '1312', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '18'],
            ['number' => '1301', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '10'],
            ['number' => '1302', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '10'],
            ['number' => '1304', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '10'],
            ['number' => '1305', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '20'],
            ['number' => '1306', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '28'],
            ['number' => '1308', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '28'],
            ['number' => '1307', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '28'],
            ['number' => '1309', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '28'],
            ['number' => '1311', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '18'],
            ['number' => '1316', 'location' => '16 мкр., дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '44'],
            ['number' => 'ДОТ', 'location' => 'Свободное местоположение', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '_'],
            ['number' => '1204', 'location' => '16 мкр. дом 33', 'responsible_person' => 'Кузнецова М.М.', 'seats' => '14'],
        ];

        foreach ($audiences as $audience) {
            RequestsAudience::create($audience);
        }

        $this->command->info('Аудитории успешно загружены: ' . count($audiences) . ' записей');
    }
}