@props([
    'title',
    'breadcrumb',
    'indexRoute',
    'createRoute',
    'createLabel' => '+ Добавить',
    'searchPlaceholder' => 'Поиск...',
    'items',
    'columns',
    'editRoute',
    'destroyRoute',
    'emptyText' => 'Записей не найдено',
])

<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('directories.index') }}" class="suzipo-back-btn" title="Назад к справочникам">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('directories.index') }}" style="color: var(--text-secondary); text-decoration: none;">Справочники</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">{{ $breadcrumb }}</span>
            </div>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: rgba(16, 185, 129, 0.12); border: 1px solid #10B981; color: #10B981; border-radius: 10px; font-size: 13px; margin-bottom: 18px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Заголовок, поиск и кнопка добавления -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px;">
            <h1 class="suzipo-form-title" style="margin-bottom: 0;">{{ $title }}</h1>

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                <form method="GET" action="{{ route($indexRoute) }}" id="searchForm" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                    <div style="position: relative; width: 280px; max-width: 100%;">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="{{ $searchPlaceholder }}"
                               class="suzipo-field"
                               style="padding-left: 34px !important;"
                               id="searchInput"
                               autocomplete="off">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                             style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </div>

                    @if(request('search'))
                        <a href="{{ route($indexRoute) }}" class="btn-suzipo-cancel" style="padding: 8px 14px;">
                            Сбросить
                        </a>
                    @endif
                </form>

                <a href="{{ route($createRoute) }}"
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background-color: var(--pill-btn-bg); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 10px; font-size: 13px; font-weight: 500; text-decoration: none;">
                    {{ $createLabel }}
                </a>
            </div>
        </div>

        <!-- Двухслойная таблица Figma -->
        <div class="suzipo-table-shell">
            <table class="suzipo-table-head">
                <colgroup>
                    @foreach($columns as $col)
                        <col style="width: {{ $col['width'] ?? 'auto' }};">
                    @endforeach
                    <col style="width: 96px;">
                </colgroup>
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th style="text-align: {{ $col['align'] ?? 'left' }};">{{ $col['label'] }}</th>
                        @endforeach
                        <th style="text-align: right; padding-right: 14px;">Действия</th>
                    </tr>
                </thead>
            </table>

            <div class="suzipo-table-body-box">
                <table class="suzipo-table-body">
                    <colgroup>
                        @foreach($columns as $col)
                            <col style="width: {{ $col['width'] ?? 'auto' }};">
                        @endforeach
                        <col style="width: 96px;">
                    </colgroup>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                @foreach($columns as $col)
                                    @php
                                        $val = data_get($item, $col['field']);
                                        if (($col['type'] ?? null) === 'date' && $val) {
                                            $val = \Carbon\Carbon::parse($val)->format('d.m.Y');
                                        }
                                    @endphp
                                    <td style="text-align: {{ $col['align'] ?? 'left' }}; {{ ($col['bold'] ?? false) ? 'font-weight: 500;' : 'color: var(--table-head-text);' }}"
                                        title="{{ $val }}">
                                        {{ $val ?? '—' }}
                                    </td>
                                @endforeach

                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 4px;">
                                        <a href="{{ route($editRoute, $item) }}" class="suzipo-action-circle" title="Редактировать">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route($destroyRoute, $item) }}" method="POST" style="display: inline-flex; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="suzipo-action-circle" style="background-color: rgba(239, 68, 68, 0.14); color: #EF4444;" title="Удалить" onclick="return confirm('Удалить?')">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" style="padding: 28px; text-align: center; color: var(--table-head-text); font-size: 13px;">
                                    {{ request('search') ? 'По запросу "' . request('search') . '" ничего не найдено.' : $emptyText }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Пагинация _TableFooter -->
            @php
                $paginator = $items->appends(request()->query());
                $curPage   = $paginator->currentPage();
                $lastPage  = $paginator->lastPage();
                $perPage   = $paginator->perPage();
                $totalRows = $paginator->total();
                $firstItem = $paginator->firstItem() ?? 0;

                $allLimits = array(10, 25, 50, 100);
                $availableLimits = array();
                foreach ($allLimits as $idx => $lim) {
                    if ($idx === 0 || $totalRows > $allLimits[$idx - 1]) {
                        $availableLimits[] = $lim;
                    }
                }

                $pages = collect([1, 2, 3, $curPage - 1, $curPage, $curPage + 1, $lastPage - 1, $lastPage])
                    ->filter(fn($p) => $p >= 1 && $p <= $lastPage)
                    ->unique()->sort()->values();
            @endphp
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 10px 8px 10px 16px; min-height: 56px; gap: 16px; font-size: 14px; color: var(--table-head-text);">
                <div style="display: flex; align-items: center; gap: 12px;" x-data="{ perPageOpen: false }">
                    <span>Показывать</span>
                    <div style="position: relative; display: inline-flex; align-items: center;">
                        <button type="button" @click="perPageOpen = !perPageOpen" @click.away="perPageOpen = false"
                                style="display: inline-flex; align-items: center; justify-content: space-between; gap: 8px; min-width: 83px; height: 36px; background-color: var(--table-body-bg); color: var(--table-row-text); border: none; border-radius: 8px; padding: 8px 12px; font-size: 14px; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.06);">
                            <span>{{ $totalRows > 0 ? $firstItem . '-' . $paginator->lastItem() : '0' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                        </button>
                        <div x-show="perPageOpen" x-cloak
                             style="position: absolute; bottom: calc(100% + 6px); left: 0; min-width: 96px; padding: 4px; border-radius: 12px; background-color: var(--table-body-bg); border: 1px solid var(--border-color); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.14); z-index: 999;">
                            @foreach($availableLimits as $limit)
                                @php
                                    $rangeStart = $totalRows > 0 ? $firstItem : 1;
                                    $rangeEnd   = $totalRows > 0 ? min($firstItem + $limit - 1, $totalRows) : $limit;
                                @endphp
                                <button type="button" onclick="changeDirPerPage({{ $limit }})"
                                        style="width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 7px 12px; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; {{ $perPage === $limit ? 'background-color: var(--pill-btn-bg); font-weight: 600; color: var(--table-row-text);' : 'background: transparent; color: var(--table-row-text);' }}">
                                    <span>{{ $totalRows > 0 ? $rangeStart . '-' . $rangeEnd : '0' }}</span>
                                    @if($perPage === $limit) <span style="color: #D97706;">✓</span> @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <span>из {{ number_format($totalRows, 0, '', ' ') }}</span>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    @if ($paginator->onFirstPage())
                        <span style="display: inline-flex; align-items: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; opacity: 0.5;">‹ Назад</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" style="display: inline-flex; align-items: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); text-decoration: none;">‹ Назад</a>
                    @endif

                    @foreach($pages as $idx => $page)
                        @if($idx > 0 && $page - $pages[$idx - 1] > 1)
                            <span style="padding: 0 2px;">...</span>
                        @endif
                        @if($page === $curPage)
                            <span style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; background-color: var(--pill-btn-bg); color: var(--table-row-text); font-weight: 500;">{{ $page }}</span>
                        @else
                            <a href="{{ $paginator->url($page) }}" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; color: var(--table-row-text); text-decoration: none;">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" style="display: inline-flex; align-items: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); text-decoration: none;">Вперёд ›</a>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; opacity: 0.5;">Вперёд ›</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeDirPerPage(limit) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', limit);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => { this.form.submit(); }, 400);
            });
            document.addEventListener('DOMContentLoaded', function() {
                if (searchInput.value) {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }
            });
        }
    </script>
</x-layouts.app-with-sidebar>
