<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Запускаем все сидеры из папки
        $seeders = glob(database_path('seeders/*.php'));

        foreach ($seeders as $seeder) {
            $className = 'Database\\Seeders\\' . pathinfo($seeder, PATHINFO_FILENAME);
            
            // Пропускаем сам DatabaseSeeder и RoleSeeder (чтобы роли создались только один раз через RoleSeeder)
            if ($className !== DatabaseSeeder::class && $className !== RoleSeeder::class) {
                $this->call($className);
            }
        }

        // Вызываем RoleSeeder отдельно (он создаст роли и пользователей)
        $this->call(RoleSeeder::class);
        
        // Ниже ничего не нужно, т.к. RoleSeeder уже создаст всех пользователей
    }
}