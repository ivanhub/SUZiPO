<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto"
        x-data="{
            searchId: '{{ request('search_id', '') }}',
            searchNum: '{{ request('search_num', '') }}',
            searchCourse: '{{ request('search_course', '') }}',
            currentStatus: '{{ $status }}',

            fetchData() {
                let params = new URLSearchParams({
                    status: this.currentStatus,
                    search_id: this.searchId,
                    search_num: this.searchNum,
                    search_course: this.searchCourse
                });

                fetch(`{{ route('demands.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('table-body').innerHTML = html;
                })
                .catch(error => console.error('Ошибка при поиске:', error));
            }
         }">

        <!-- Заголовок страницы -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Реестр заявок на обучение</h1>
            <p class="text-sm text-gray-500 mt-1">Система автоматизации корпоративного обучения (СУЗиПО)</p>
        </div>

        <!-- Вкладки фильтрации по кодам статусов (пример для кодов 1, 2, 3) -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex flex-wrap space-x-6">
                <a href="{{ route('demands.index', ['status' => 'all']) }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 inline-flex items-center space-x-2
                   {{ $status === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span>Все заявки</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                        {{ $totalCount }}
                    </span>
                </a>
                <a href="{{ route('demands.index', ['status' => '1']) }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 inline-flex items-center space-x-2
                   {{ $status === '1' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span>Статус 1</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                        {{ $counts['1'] ?? 0 }}
                    </span>
                </a>
            </nav>
        </div>

        <!-- Основной контейнер таблицы данных -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">ID</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">№ / Год</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Даты оформления</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Пользователи / Сессия</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Курс / УЗ</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Локация / Профессия</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Период обучения</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Вид / Форма / Док</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Служебные</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Действия</th>
                        </tr>
                        <!-- Фильтры -->
                        <tr class="bg-gray-100/50 border-b border-gray-200">
                            <td class="p-1"><input type="text" x-model="searchId" @input.debounce.400ms="fetchData()" class="w-full text-xs rounded border-gray-300 py-0.5 px-1" placeholder="ID"></td>
                            <td class="p-1"><input type="text" x-model="searchNum" @input.debounce.400ms="fetchData()" class="w-full text-xs rounded border-gray-300 py-0.5 px-1" placeholder="№"></td>
                            <td colspan="7" class="text-center text-xs text-gray-400 font-normal py-1"></td>
                            <td class="p-1"></td>
                        </tr>
                    </thead>

                    <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                        @include('demands.partials.table-rows')
                    </tbody>
                </table>
            </div>

            <!-- Пагинация Назад / Вперед -->
            @if(method_exists($demands, 'links'))
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $demands->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layouts.app-with-sidebar>