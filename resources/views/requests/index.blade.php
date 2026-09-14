<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Блок успешных уведомлений -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    @if(session('request_id'))
                    <a href="{{ route('request-employees.index', session('request_id')) }}"
                        class="ml-4 inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition">
                        Добавить сотрудников →
                    </a>
                    @endif
                </div>
                @endif

                <!-- Блок уведомлений об ошибках -->
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <!-- Начало формы для массовой отправки -->
                <!-- Измененная Часть 1 -->
                <form id="mass-send-form" action="{{ route('requests.send-to-ooo') }}" method="POST">
                    @csrf

                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <h2 class="text-lg font-semibold text-gray-900">Заявки на обучение</h2>

                            <button type="submit" id="btn-mass-send"
                                class="hidden inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition"
                                onclick="return confirm('Отправить выбранные заявки в ООО?')">
                                🚀 Отправить выбранные в ООО
                            </button>
                        </div>

                        <a href="{{ route('requests.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            + Создать заявку
                        </a>
                    </div>
                </form> <!-- ЗАКРЫВАЕМ ФОРМУ ТУТ, чтобы она не поглощала таблицу -->

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed; width: 100%;">
                        <!-- Далее идет ваша шапка таблицы (thead) без изменений -->

                        <thead class="bg-gray-50">
                            <tr>
                                <!-- Общий чекбокс выбора -->
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase" style="width: 40px;">
                                    <input type="checkbox" id="select-all-requests" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 40px;">ID</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Статус</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 55px;">Разов.</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 85px;">Дата нач.</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 85px;">Дата окон.</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 180px;">Курс</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 180px;">Провайдер</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Автор</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 75px;">Создана</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 110px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <!-- Чекбокс строки -->
                                <!-- Измененный чекбокс внутри <tbody> -->
                                <td class="px-2 py-4 whitespace-nowrap text-sm">
                                    @if($request->status === 'Создана')
                                    <!-- Добавлен атрибут form="mass-send-form" -->
                                    <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" form="mass-send-form" class="request-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    @else
                                    <input type="checkbox" disabled class="rounded border-gray-200 bg-gray-100 cursor-not-allowed">
                                    @endif
                                </td>


                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->id }}</td>
                                <td class="px-1 py-4 whitespace-nowrap">
                                    <span class="px-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($request->status === 'Создана') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'Отправлена') bg-blue-100 text-blue-800
                                            @elseif($request->status === 'accepted') bg-green-100 text-green-800
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->one_time ? 'Да' : 'Нет' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->start_date ? $request->start_date->format('d.m') : '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->end_date ? $request->end_date->format('d.m') : '—' }}</td>
                                <td class="px-1 py-4 text-sm text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->course->course ?? '' }}">{{ Str::limit($request->course->course ?? '—', 40) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->provider->name ?? '' }}">{{ Str::limit($request->provider->name ?? '—', 40) }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis;" title="{{ $request->user->name ?? '' }}">{{ $request->user->name ?? '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('d.m.Y') }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">

                                        <!-- Одиночная отправка -->
                                        @if($request->status === 'Создана')
                                        <button type="button"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-bold border border-blue-300 rounded px-1"
                                            title="Отправить одну заявку"
                                            data-id="{{ $request->id }}"
                                            onclick="sendSingleRequest(this)">📤
                                        </button>
                                        @endif

                                        <!-- Посмотреть / Редактировать / Сотрудники -->
                                        <a href="{{ route('requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900" title="Посмотреть">👁️</a>
                                        <a href="{{ route('requests.edit', $request) }}" class="text-indigo-600 hover:text-indigo-900" title="Редактировать">✏️</a>
                                        <a href="{{ route('request-employees.index', $request->id) }}" class="text-green-600 hover:text-green-900 relative" title="Сотрудники">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            @if($request->employees_count > 0)
                                            <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $request->employees_count }}</span>
                                            @endif
                                        </a>
                                        <form action="{{ route('requests.destroy', $request) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить" onclick="return confirm('Удалить?')">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-1 py-4 text-center text-sm text-gray-500">Заявок пока нет</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                


                <!-- Пагинация страниц -->
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Логика работы UI на JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-requests');
            const rowCheckboxes = document.querySelectorAll('.request-checkbox');
            const massSendBtn = document.getElementById('btn-mass-send');

            // Показ кнопки массовой отправки при наличии выбранных элементов
            function toggleMassButton() {
                const checkedCount = document.querySelectorAll('.request-checkbox:checked').length;
                if (checkedCount > 0) {
                    massSendBtn.classList.remove('hidden');
                } else {
                    massSendBtn.classList.add('hidden');
                }
            }

            // Выбрать/снять все чекбоксы одной галочкой
            selectAllCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => {
                    if (!cb.disabled) {
                        cb.checked = selectAllCheckbox.checked;
                    }
                });
                toggleMassButton();
            });

            // Слушатель для каждой отдельной галочки в строке
            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    toggleMassButton();
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    }
                });
            });
        });

        // Функция обработки клика по иконке одиночной отправки в строке
        function sendSingleRequest(button) {
            // Достаем ID из data-атрибута нажатой кнопки
            const id = button.getAttribute('data-id');

            if (confirm('Отправить эту заявку в ООО?')) {
                const form = document.getElementById('mass-send-form');

                // Сброс остальных чекбоксов
                document.querySelectorAll('.request-checkbox').forEach(cb => cb.checked = false);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'request_ids[]';
                input.value = id;

                form.appendChild(input);
                form.submit();
            }
        }
    </script>
</x-layouts.app-with-sidebar>