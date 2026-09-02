<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        
        // Создаём админа
        $admin = User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@suzipo.ru',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');
        
        // Создаём пользователей с разными ролями
        $this->createUsersWithRole('umo', 'umo', 2);
        $this->createUsersWithRole('methodist', 'methodist', 3);
        $this->createUsersWithRole('curator', 'curator', 2);
        $this->createUsersWithRole('user', 'user', 10);
    }
    
    private function createUsersWithRole(string $role, string $prefix, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            // Создаём email в формате: role1@suzipo.ru, role2@suzipo.ru и т.д.
            $email = $prefix . ($i + 1) . '@suzipo.ru';
            
            $user = User::factory()->create([
                'email' => $email, // Явно указываем email
            ]);
            
            $user->assignRole($role);
        }
    }
}