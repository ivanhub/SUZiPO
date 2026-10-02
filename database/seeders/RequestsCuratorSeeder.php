<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestsCurator;

class RequestsCuratorSeeder extends Seeder
{
    public function run(): void
    {
        $curators = [
            [
                'fio' => 'Анисимова Алёна Александровна',
                'email' => 'AA_Anisimova1@ung.rosneft.ru',
            ],
            [
                'fio' => 'Боцунова Елена Викторовна',
                'email' => 'EV_Botsunova2@ung.rosneft.ru',
            ],
            [
                'fio' => 'Гусева Ольга Маркеловна',
                'email' => 'OM_Guseva@ung.rosneft.ru',
            ],
            [
                'fio' => 'Курочка Елена Александровна',
                'email' => 'EA_Kurochka@ung.rosneft.ru',
            ],
            [
                'fio' => 'Лазорская Лариса Анатольевна',
                'email' => 'LA_Lazorskaya@ung.rosneft.ru',
            ],
            [
                'fio' => 'Миловидова Инна Евгеньевна',
                'email' => 'IE_Milovidova@ung.rosneft.ru',
            ],
            [
                'fio' => 'Миронова Лариса Владимировна',
                'email' => 'LV_Mironova2@ung.rosneft.ru',
            ],
            [
                'fio' => 'Морская Валентина Николаевна',
                'email' => 'VN_Morskaya@ung.rosneft.ru',
            ],
            [
                'fio' => 'Педан Наталья Константиновна',
                'email' => 'NK_Pedan@ung.rosneft.ru',
            ],
            [
                'fio' => 'Прокопьева Наталья Николаевна',
                'email' => 'NN_Prokopeva@ung.rosneft.ru',
            ],
            [
                'fio' => 'Селезнева Татьяна Ивановна',
                'email' => 'TI_Selezneva@ung.rosneft.ru',
            ],
            [
                'fio' => 'Сокурова Екатерина Александровна',
                'email' => 'EA_Sokurova@ung.rosneft.ru',
            ],
            [
                'fio' => 'Шелухина Наталья Ивановна',
                'email' => 'NI_Shelukhina@ung.rosneft.ru',
            ],
        ];

        foreach ($curators as $curator) {
            RequestsCurator::firstOrCreate(
                ['email' => $curator['email']],
                $curator
            );
        }

        $this->command->info('Добавлено кураторов: ' . count($curators));
    }
}