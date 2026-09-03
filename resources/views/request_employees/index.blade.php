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

                <!-- Заголовок -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Сотрудники заявки #{{ $trainingRequest->id }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Программа обучения: <span class="font-medium">{{ $trainingRequest->course->course ?? 'Не указана' }}</span>
                    </p>
                </div>

                <!-- Форма добавления сотрудника -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-md font-medium text-gray-700 mb-3">Добавить сотрудника</h3>
                    <form action="{{ route('request-employees.store', $trainingRequest->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tab_number" class="block text-sm font-medium text-gray-700 mb-1">Табельный номер</label>
                                <input type="text" name="tab_number" id="tab_number" value="{{ old('tab_number') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Введите табельный номер">
                                @error('tab_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                    Добавить сотрудника
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
<!-- Форма пакетного добавления сотрудников -->
<div class="bg-blue-50 p-4 rounded-lg mb-6">
    <h3 class="text-md font-medium text-gray-700 mb-3">Пакетное добавление сотрудников</h3>
    <form action="{{ route('request-employees.store-bulk', $trainingRequest->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="tab_numbers" class="block text-sm font-medium text-gray-700 mb-1">
                    Табельные номера (каждый с новой строки)
                </label>
                <textarea name="tab_numbers" id="tab_numbers" rows="8"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono"
                          placeholder="10000066&#10;10000069&#10;10000071&#10;..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Введите табельные номера, каждый с новой строки</p>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Добавить всех сотрудников
                </button>
            </div>
        </div>
    </form>
</div>
<!-- Форма ручного добавления сотрудника -->
<div class="bg-purple-50 p-4 rounded-lg mb-6">
    <h3 class="text-md font-medium text-gray-700 mb-3">Ручной ввод сотрудника</h3>
    <form action="{{ route('request-employees.store', $trainingRequest->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="manual_last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия *</label>
                <input type="text" name="last_name" id="manual_last_name" value="{{ old('last_name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       required>
            </div>
            <div>
                <label for="manual_first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя *</label>
                <input type="text" name="first_name" id="manual_first_name" value="{{ old('first_name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       required>
            </div>
            <div>
                <label for="manual_middle_name" class="block text-sm font-medium text-gray-700 mb-1">Отчество</label>
                <input type="text" name="middle_name" id="manual_middle_name" value="{{ old('middle_name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>
        <div class="flex items-end mt-4">
            <button type="submit" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                Добавить сотрудника
            </button>
        </div>
    </form>
</div>

                <!-- Список сотрудников -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 60px;">Таб.№</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">ФИО</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Должность</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Дата начала</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Дата окончания</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Форма</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Статус</th>
				<th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Дата выдачи документа</th>
				<th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Периодичность</th>

                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Действия</th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($employees as $employee)
                            <tr class="hover:bg-gray-50 {{ $employee->status == 'blocked' ? 'bg-red-50' : '' }} {{ $employee->status == 'warning' ? 'bg-yellow-50' : '' }} {{ $employee->status == 'expired' ? 'bg-red-100' : '' }} {{ $employee->status == 'dismissed' ? 'bg-gray-100' : '' }}">
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ $employee->tab_number ?? '—' }}</td>
<td class="px-2 py-4 text-sm text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $employee->full_name }}">
    {{ $employee->full_name ?? '—' }}
    @if(!$employee->userSap)
        <span class="ml-1 px-1 py-0.5 bg-purple-100 text-purple-800 rounded text-xs">нет в SAP</span>
    @endif
</td>                                <td class="px-2 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $employee->position }}">{{ $employee->position ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->absence_start_date ? $employee->absence_start_date->format('d.m.Y') : '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->absence_end_date ? $employee->absence_end_date->format('d.m.Y') : '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->absence_type ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm">
                                    @if($employee->status == 'active')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Активен</span>
                                    @elseif($employee->status == 'blocked')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Заблокирован</span>
                                    @elseif($employee->status == 'warning')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Внимание</span>
                                    @elseif($employee->status == 'expired')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Просрочено</span>
                                    @elseif($employee->status == 'dismissed')
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Уволен</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $employee->status }}</span>
                                    @endif
                                </td>
                                     <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">
    {{ $employee->document_issue_date ? $employee->document_issue_date->format('d.m.Y') : '—' }}
</td>
<td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->reissue_period ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
<!-- Кнопка редактирования -->
<button type="button" 
        class="text-indigo-600 hover:text-indigo-900 mr-2 edit-btn"
        data-id="{{ $employee->id }}"
        data-request-id="{{ $trainingRequest->id }}"
        data-start="{{ $employee->absence_start_date ? $employee->absence_start_date->format('Y-m-d') : '' }}"
        data-end="{{ $employee->absence_end_date ? $employee->absence_end_date->format('Y-m-d') : '' }}"
        data-type="{{ $employee->absence_type }}"
        data-distance="{{ $employee->distance_learning_date ? $employee->distance_learning_date->format('Y-m-d') : '' }}"
        data-fulltime="{{ $employee->fulltime_learning_date ? $employee->fulltime_learning_date->format('Y-m-d') : '' }}"
        data-note="{{ $employee->note }}"
        data-doc-date="{{ $employee->document_issue_date ? $employee->document_issue_date->format('Y-m-d') : '' }}"
        data-period="{{ $employee->reissue_period }}"
        onclick="openEditModal(this)">✏️</button>


<!-- Кнопка удаления -->
<button type="button" 
        class="text-red-600 hover:text-red-900 delete-btn"
        data-id="{{ $employee->id }}"
        data-request-id="{{ $trainingRequest->id }}"
        onclick="deleteEmployee(this)">🗑️</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-2 py-4 text-center text-sm text-gray-500">Сотрудники не добавлены</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Ссылка назад -->
                <div class="mt-4">
                    <a href="{{ route('requests.index') }}" class="text-indigo-600 hover:text-indigo-900">← Назад к заявкам</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-gray-500 opacity-75" onclick="closeEditModal()"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Редактирование сотрудника</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_absence_start_date" class="block text-sm font-medium text-gray-700 mb-1">Дата начала отсутствия</label>
                            <input type="date" name="absence_start_date" id="edit_absence_start_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="edit_absence_end_date" class="block text-sm font-medium text-gray-700 mb-1">Дата окончания отсутствия</label>
                            <input type="date" name="absence_end_date" id="edit_absence_end_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="edit_absence_type" class="block text-sm font-medium text-gray-700 mb-1">Форма обучения</label>
                            <select name="absence_type" id="edit_absence_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    onchange="toggleDistanceFields()">
                                <option value="">Выберите форму</option>
                                <option value="очно">Очно</option>
                                <option value="заочно">Заочно</option>
                                <option value="очно-заочное">Очно-заочное</option>
                            </select>
                        </div>
                        
                        <div id="distance_fields" class="hidden md:col-span-2 grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit_distance_learning_date" class="block text-sm font-medium text-gray-700 mb-1">Дата заочного обучения</label>
                                <input type="date" name="distance_learning_date" id="edit_distance_learning_date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="edit_fulltime_learning_date" class="block text-sm font-medium text-gray-700 mb-1">Дата очного обучения</label>
                                <input type="date" name="fulltime_learning_date" id="edit_fulltime_learning_date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="edit_document_issue_date" class="block text-sm font-medium text-gray-700 mb-1">Дата выдачи документа</label>
                            <input type="date" name="document_issue_date" id="edit_document_issue_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="edit_reissue_period" class="block text-sm font-medium text-gray-700 mb-1">Периодичность обучения</label>
                            <select name="reissue_period" id="edit_reissue_period"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Выберите периодичность</option>
                                <option value="6 месяцев">6 месяцев</option>
                                <option value="1 год">1 год</option>
                                <option value="2 года">2 года</option>
                                <option value="3 года">3 года</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="edit_note" class="block text-sm font-medium text-gray-700 mb-1">Примечание</label>
                            <textarea name="note" id="edit_note" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeEditModal()" 
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</button>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Открытие модального окна редактирования
    function openEditModal(button) {
        const employeeId = button.dataset.id;
        const requestId = button.dataset.requestId;
        const form = document.getElementById('editForm');
        
        form.action = `/request-employees/${requestId}/${employeeId}`;
        
        document.getElementById('edit_absence_start_date').value = button.dataset.start;
        document.getElementById('edit_absence_end_date').value = button.dataset.end;
        document.getElementById('edit_absence_type').value = button.dataset.type;
        document.getElementById('edit_distance_learning_date').value = button.dataset.distance;
        document.getElementById('edit_fulltime_learning_date').value = button.dataset.fulltime;
        document.getElementById('edit_document_issue_date').value = button.dataset.docDate;
        document.getElementById('edit_reissue_period').value = button.dataset.period;
        document.getElementById('edit_note').value = button.dataset.note;
        
        toggleDistanceFields(); // Вызываем функцию (она определена ниже)
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Закрытие модального окна
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Показать/скрыть поля для очно-заочной формы
    function toggleDistanceFields() {
        const type = document.getElementById('edit_absence_type').value;
        const distanceFields = document.getElementById('distance_fields');
        const distanceDate = document.getElementById('edit_distance_learning_date');
        const fulltimeDate = document.getElementById('edit_fulltime_learning_date');
        
        if (type === 'очно-заочное') {
            distanceFields.classList.remove('hidden');
            distanceDate.disabled = false;
            fulltimeDate.disabled = false;
        } else {
            distanceFields.classList.add('hidden');
            distanceDate.disabled = true;
            fulltimeDate.disabled = true;
        }
    }

    // Удаление сотрудника
    function deleteEmployee(button) {
        if (!confirm('Удалить сотрудника из заявки?')) return;
        
        const employeeId = button.dataset.id;
        const requestId = button.dataset.requestId;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/request-employees/${requestId}/${employeeId}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Инициализация при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        toggleDistanceFields();
    });

// Очистка поля после успешного добавления
@if(session('success'))
    document.getElementById('tab_numbers').value = '';
@endif

</script></x-layouts.app-with-sidebar>