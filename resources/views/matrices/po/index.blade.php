<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Поиск -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('matrices.po.index') }}" id="searchForm">
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Поиск по ПО..."
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
                                <a href="{{ route('matrices.po.index') }}" 
                                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                    Сбросить
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Матрица ПО (Профессиональное обучение)</h2>
                    <a href="{{ route('matrices.po.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        + Добавить
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 40px;">№</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Шифр</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Вид ПО</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Профессия</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Разряд</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 70px;">Часы</th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($matrixPos as $po)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ $po->number ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $po->code ?? '—' }}</td>
                                <td class="px-2 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $po->education_type }}">{{ $po->education_type ?? '—' }}</td>
                                <td class="px-2 py-4 text-sm text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $po->profession_name }}">{{ $po->profession_name ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $po->rank ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $po->hours ?? '—' }}</td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('matrices.po.edit', $po) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">✏️</a>
                                    <form action="{{ route('matrices.po.destroy', $po) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Удалить?')">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-2 py-4 text-center text-sm text-gray-500">Записей не найдено</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $matrixPos->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 400);
            });
            
            document.addEventListener('DOMContentLoaded', function() {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            });
        }
    </script>
</x-layouts.app-with-sidebar>