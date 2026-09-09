<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Редактирование пользователя') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Имя:</label>
                        <input type="text" name="name" id="name" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" name="email" id="email" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            Новый пароль (оставьте пустым, если не хотите менять):
                        </label>
                        <input type="password" name="password" id="password" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Подтверждение нового пароля:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Роли:</label>
                        @foreach($roles as $role)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                       id="role-{{ $role->id }}"
                                       class="mr-2"
                                       {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        @error('roles')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>