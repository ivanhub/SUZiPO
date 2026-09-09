<x-layouts.app-with-sidebar>
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

<!-- Drag & Drop Загрузка -->
<div class="mb-6">
    <div id="dropZone" 
         class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition-colors cursor-pointer max-w-md mx-auto">
        <div class="flex justify-center mb-2">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 150px; height: 150px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
        </div>
        <p class="text-sm text-gray-600">Перетащите файл сюда или <span class="text-indigo-600 font-medium">выберите файл</span></p>
        <p class="text-xs text-gray-400 mt-1">Поддерживаются форматы: XLSX, XLS, CSV</p>
        <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" class="hidden">
    </div>
</div>
                <!-- Поиск -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('all-users-sap.index') }}" id="searchForm">
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Поиск по фамилии, имени, табельный номер..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       id="searchInput"
                                       autocomplete="off">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @if(request('search'))
                                <a href="{{ route('all-users-sap.index') }}" 
                                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                    Сбросить
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Все пользователи SAP</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('all-users-sap.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            + Добавить
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 60px;">Таб.№</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">ФИО</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Дата рождения</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Пол</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Должность</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Подразделение</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->tab_number ?? '—' }}</td>
                                <td class="px-2 py-4 text-sm text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $user->full_name }}">{{ $user->full_name ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->birth_date ? $user->birth_date->format('d.m.Y') : '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->gender ?? '—' }}</td>
                                <td class="px-2 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $user->position }}">{{ $user->position ?? '—' }}</td>
                                <td class="px-2 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $user->level_4_name }}">{{ $user->level_4_name ?? '—' }}</td>
<td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
    <a href="{{ route('all-users-sap.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">✏️</a>
    <form action="{{ route('all-users-sap.delete', $user) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Удалить?')">🗑️</button>
    </form>
</td>                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-2 py-4 text-center text-sm text-gray-500">Записей не найдено</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>

<script>
    // Drag & Drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    dropZone.addEventListener('click', () => fileInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            uploadFile(files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            uploadFile(e.target.files[0]);
        }
    });

function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    dropZone.innerHTML = `
        <div class="flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="ml-3 text-sm text-gray-600">Загрузка файла...</p>
        </div>
    `;

    fetch('{{ route('all-users-sap.import') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        return response.json().then(data => ({
            ok: response.ok,
            data: data
        }));
    })
    .then(({ok, data}) => {
        if (ok && data.success) {
            let message = `Успешно импортировано: ${data.count} записей.`;
            if (data.errors && data.errors.length > 0) {
                message += ` Ошибок: ${data.errors.length}. Первые ошибки: ${data.errors.slice(0, 3).join('; ')}`;
            }
            console.log('Информация о файле:', data.file_info);
            alert(message);
        } else {
            console.error('Ошибка сервера:', data);
            alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
        }
        window.location.reload();
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert('Ошибка при загрузке файла: ' + error.message);
        window.location.reload();
    });
}

    // Поиск с автоотправкой
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 400);
        });
    }
</script>
    <!-- Скрытая форма для загрузки файла -->
    <form id="uploadForm" action="{{ route('all-users-sap.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" name="file" id="uploadFileInput">
    </form>
</x-layouts.app-with-sidebar>