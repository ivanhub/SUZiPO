<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Управление пользователями') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- ЕДИНАЯ ФОРМА для всех фильтров -->
                <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                    
                    <!-- Верхняя панель: общий поиск + кнопки -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                        <div class="flex-1">
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ $search }}"
                                           placeholder="Общий поиск по всем полям..."
                                           class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           autocomplete="off"
                                           id="globalSearch">
                                    <div class="absolute left-3 top-2.5 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <select name="role" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onchange="this.form.submit()">
                                    <option value="">Все роли</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $roleFilter == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Поиск
                                </button>

                                @if($hasActiveFilters)
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        Сбросить
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('admin.users.create') }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                + Новый пользователь
                            </a>
                        </div>
                    </div>

                    <!-- Результаты поиска -->
                    <div class="mb-4 text-sm text-gray-600">
                        Найдено: <strong>{{ $users->total() }}</strong> пользователей
                    </div>

                    <!-- Кнопки экспорта -->
                    <div class="mb-4 flex gap-2">
<button type="submit" 
        name="export" 
        value="excel"
        formaction="{{ route('admin.users.export') }}"
        formmethod="GET"
        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    Экспорт всех ({{ $users->total() }})
</button>                        <button type="button" 
                                id="exportSelectedBtn"
                                onclick="exportSelected()"
                                class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Экспорт выбранных (<span id="selectedCount">0</span>)
                        </button>
                    </div>

                    <!-- Таблица пользователей -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center">
                                        <input type="checkbox" 
                                               id="selectAll" 
                                               onchange="toggleSelectAll(this)"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                        <div class="mt-1">
                                            <input type="text" name="filter_id" value="{{ $filterId }}" 
                                                   placeholder="ID"
                                                   class="w-20 px-2 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Имя
                                        <div class="mt-1">
                                            <input type="text" name="filter_name" value="{{ $filterName }}" 
                                                   placeholder="Имя"
                                                   class="w-24 px-2 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                        <div class="mt-1">
                                            <input type="text" name="filter_email" value="{{ $filterEmail }}" 
                                                   placeholder="Email"
                                                   class="w-24 px-2 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Сотрудник ID
                                        <div class="mt-1">
                                            <input type="text" name="filter_employee_id" value="{{ $filterEmployeeId }}" 
                                                   placeholder="Сотр.ID"
                                                   class="w-20 px-2 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Должность
                                        <div class="mt-1">
                                            <input type="text" name="filter_position" value="{{ $filterPosition }}" 
                                                   placeholder="Должн."
                                                   class="w-20 px-2 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Отдел
                                        <div class="mt-1">
                                            <input type="text" name="filter_department" value="{{ $filterDepartment }}" 
                                                   placeholder="Отдел"
                                                   class="w-20 px-2 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Роли
                                        <div class="mt-1">
                                            <select name="filter_roles" class="w-24 px-1 py-1 text-xs border rounded"
                                                    onchange="this.form.submit()">
                                                <option value="">Все</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ $filterRoles == $role->name ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Дата создания
                                        <div class="mt-1">
                                            <input type="date" name="filter_created_at" value="{{ $filterCreatedAt }}" 
                                                   class="w-24 px-1 py-1 text-xs border rounded"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" 
                                               class="user-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               value="{{ $user->id }}"
                                               onchange="updateSelectedCount()">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->employee_id ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->position ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->department ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                            <span class="text-gray-400">—</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="text-blue-600 hover:text-blue-900" title="Просмотр">👁️</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" title="Редактировать">✏️</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Удалить"
                                                        onclick="return confirm('Удалить пользователя {{ $user->name }}?')">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                        Пользователи не найдены.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" name="page" value="{{ request('page') }}">
                </form>

                <!-- Пагинация -->
                <div class="mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
// Автоматический поиск при вводе в общее поле (с задержкой)
let searchTimeout;
document.getElementById('globalSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        this.form.submit();
    }, 500);
});

// Выделить все чекбоксы
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    updateSelectedCount();
}

// Обновить счётчик выбранных
function updateSelectedCount() {
    const selected = document.querySelectorAll('.user-checkbox:checked');
    document.getElementById('selectedCount').textContent = selected.length;
    
    // Обновить состояние кнопки "Выделить всё"
    const totalCheckboxes = document.querySelectorAll('.user-checkbox').length;
    const checkedCheckboxes = selected.length;
    document.getElementById('selectAll').checked = totalCheckboxes === checkedCheckboxes;
}

// Экспорт выбранных пользователей
function exportSelected() {
    const selected = document.querySelectorAll('.user-checkbox:checked');
    
    if (selected.length === 0) {
        alert('Пожалуйста, выберите хотя бы одного пользователя.');
        return;
    }

    // Создаём форму и отправляем POST запрос
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('admin.users.export-selected') }}';
    
    // CSRF токен
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    // Выбранные ID пользователей
    selected.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_users[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}
</script>