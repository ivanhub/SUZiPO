<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём роли (если их ещё нет)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $umo = Role::firstOrCreate(['name' => 'umo']);              // Специалист УМО
        $methodist = Role::firstOrCreate(['name' => 'methodist']);  // Методист
        $curator = Role::firstOrCreate(['name' => 'curator']);      // Куратор
        $user = Role::firstOrCreate(['name' => 'user']);           // Обычный пользователь
        
        // Создаём базовые разрешения
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
        
        // Назначаем разрешения ролям
        $admin->givePermissionTo(Permission::all());
        $umo->givePermissionTo(['view_requests', 'create_requests', 'edit_requests', 'view_protocols', 'view_reports', 'export_data']);
        $methodist->givePermissionTo(['view_requests', 'create_requests', 'edit_requests', 'view_protocols', 'create_protocols', 'edit_protocols']);
        $curator->givePermissionTo(['view_requests', 'view_protocols']);
        $user->givePermissionTo(['view_requests', 'create_requests']);
    }
}