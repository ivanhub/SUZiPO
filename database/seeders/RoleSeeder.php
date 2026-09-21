<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем 8 ролей
        $roles = [
            'admin',
            'urp',
            'ooo',
            'ookoit',
            'metodist',
            'ooo admin',
            'ooo chief',
            'urp admin',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Создаем права
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_requests',
            'create_requests',
            'edit_requests',
            'delete_requests',
            'view_protocols',
            'create_protocols',
            'edit_protocols',
            'delete_protocols',
            'view_reports',
            'export_data',
            'view_directories',
            'edit_directories',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Админ - полный доступ (все права)
        Role::findByName('admin')->givePermissionTo(Permission::all());
        
        // urp - создает заявки, отправляет в ООО, смотрит протоколы
        Role::findByName('urp')->givePermissionTo(['view_requests', 'create_requests', 'edit_requests', 'view_protocols', 'view_reports', 'export_data']);
        
        // ooo - просмотр заявок, редактирование (без создания)
        Role::findByName('ooo')->givePermissionTo(['view_requests', 'edit_requests', 'view_protocols']);
        
        // ookoit - только просмотр
        Role::findByName('ookoit')->givePermissionTo(['view_requests', 'view_protocols', 'view_reports']);
        
        // metodist - работа с заявками и протоколами
        Role::findByName('metodist')->givePermissionTo(['view_requests', 'edit_requests', 'view_protocols', 'create_protocols', 'edit_protocols']);
        
        // ooo admin - как ooo + отчеты и экспорт
        Role::findByName('ooo admin')->givePermissionTo(['view_requests', 'edit_requests', 'view_protocols', 'view_reports', 'export_data']);
        
        // ooo chief - как ooo + просмотр отчетов
        Role::findByName('ooo chief')->givePermissionTo(['view_requests', 'view_protocols', 'view_reports']);
        
        // urp admin - как urp + редактирование пользователей
        Role::findByName('urp admin')->givePermissionTo(['view_requests', 'create_requests', 'edit_requests', 'view_protocols', 'view_reports', 'export_data', 'view_users']);

        // Создаем пользователей для каждой роли
        $this->createUsersForRole('admin', 'admin', 3);
        $this->createUsersForRole('urp', 'urp', 3);
        $this->createUsersForRole('ooo', 'ooo', 3);
        $this->createUsersForRole('ookoit', 'ookoit', 3);
        $this->createUsersForRole('metodist', 'metodist', 3);
        $this->createUsersForRole('ooo admin', 'oooadmin', 3);
        $this->createUsersForRole('ooo chief', 'ooochief', 3);
        $this->createUsersForRole('urp admin', 'urpadmin', 3);

        // Добавляем конкретных специалистов для роли ooo
        $this->createSpecificUsersForOoo();
        
        // Создаем пользователя для уведомлений ООО
        $this->createMainOooUser();
    }

    private function createUsersForRole(string $roleName, string $prefix, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $email = $prefix . ($i > 0 ? $i : '') . '@suzipo.ru';
            
            $user = User::factory()->create([
                'name' => ucfirst($roleName) . ' User ' . ($i + 1),
                'email' => $email,
                'password' => bcrypt('p123'),
            ]);
            
            $user->assignRole($roleName);
        }
    }

    private function createSpecificUsersForOoo(): void
    {
        $specialists = [
            [
                'name' => 'Кузнецова Мария Мироновна',
                'email' => 'MM_Kuznetsova2@ung.rosneft.ru',
            ],
            [
                'name' => 'Шелухина Наталья Ивановна',
                'email' => 'NI_Shelukhina@ung.rosneft.ru',
            ],
            [
                'name' => 'Сокурова Екатерина Александровна',
                'email' => 'EA_Sokurova@ung.rosneft.ru',
            ],
            [
                'name' => 'Курочка Елена Александровна',
                'email' => 'EA_Kurochka@ung.rosneft.ru',
            ],
        ];

        foreach ($specialists as $specialist) {
            $user = User::factory()->create([
                'name' => $specialist['name'],
                'email' => $specialist['email'],
                'password' => bcrypt('p123'),
            ]);
            
            $user->assignRole('ooo');
        }
    }

    private function createMainOooUser(): void
    {
        // Пользователь для уведомлений ООО
        $mainOoo = User::factory()->create([
            'name' => 'Для уведомлений ООО',
            'email' => 'mainooo@suzipo.ru',
            'password' => bcrypt('p123'),
        ]);
        
        $mainOoo->assignRole('ooo');
    }
}