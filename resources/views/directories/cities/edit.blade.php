<!-- Поиск с автоматической фильтрацией -->
<div class="mb-4">
    <form method="GET" action="{{ route('directories.cities.index') }}" id="searchForm">
        <div class="flex gap-2">
            <div class="relative flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Поиск по городам... (регистронезависимый)"
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
                <a href="{{ route('directories.cities.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Сбросить
                </a>
            @endif
        </div>
    </form>
</div>

<script>
    // Автоматический поиск при вводе текста (с задержкой 300ms)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 300);
    });
</script>