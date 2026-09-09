<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Просмотр пользователя') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Основная информация</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Имя</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Сотрудник ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employee_id ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Должность</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->position ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Отдел</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->department ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Роли и права</h3>
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Роли</dt>
                                <dd class="mt-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($role->name === 'admin') bg-red-100 text-red-800
                                            @elseif($role->name === 'umo') bg-blue-100 text-blue-800
                                            @elseif($role->name === 'methodist') bg-green-100 text-green-800
                                            @elseif($role->name === 'curator') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400">Нет ролей</span>
                                    @endforelse
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Права</dt>
                                <dd class="mt-1">
                                    @php
                                        $permissions = $user->getAllPermissions()->pluck('name');
                                    @endphp
                                    @if($permissions->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($permissions as $permission)
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">
                                                    {{ $permission }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">Нет прав</span>
                                    @endif
                                </dd>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Даты</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Создан</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d.m.Y H:i:s') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Обновлён</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d.m.Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Назад к списку
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Редактировать
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>