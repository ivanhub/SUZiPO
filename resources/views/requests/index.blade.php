<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">

        <!-- уведомления -->
        @if(session('success'))
        <div style="padding: 12px 16px; background-color: rgba(16, 185, 129, 0.12); border: 1px solid #10B981; color: #10B981; border-radius: 10px; font-size: 13px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center;">
            <span>✅ {{ session('success') }}</span>
            @if(session('request_id'))
            <a href="{{ route('request-employees.index', session('request_id')) }}"
               style="margin-left: 16px; padding: 6px 12px; background-color: #10B981; color: #FFFFFF; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration: none;">
                Добавить сотрудников →
            </a>
            @endif
        </div>
        @endif

        <!-- Уведомления об ошибках -->
        @if(session('error'))
        <div style="padding: 12px 16px; background-color: rgba(239, 68, 68, 0.12); border: 1px solid #EF4444; color: #EF4444; border-radius: 10px; font-size: 13px; margin-bottom: 18px;">
            ⚠️ {{ session('error') }}
        </div>
        @endif

        <!-- Форма массовой отправки + Заголовок и кнопка "+ Добавить заявку" -->
        <form id="mass-send-form" action="{{ route('requests.send-to-ooo') }}" method="POST">
            @csrf
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0;">Заявки на обучение</h1>

                    @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']))
                    <button type="submit" id="btn-mass-send"
                            style="display: none; align-items: center; gap: 6px; padding: 8px 14px; background-color: #10B981; color: #FFFFFF; border: none; border-radius: 10px; font-size: 12px; font-weight: 600; cursor: pointer;"
                            onclick="return confirm('Отправить выбранные заявки в ООО?')">
                        🚀 Отправить выбранные в ООО
                    </button>
                    @endif
                </div>

                <a href="{{ route('requests.create') }}"
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background-color: var(--pill-btn-bg); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 10px; font-size: 13px; font-weight: 500; text-decoration: none;">
                    <span style="font-size: 16px; line-height: 1;">+</span> Добавить заявку
                </a>
            </div>
        </form>

        <!-- Внешняя серая оболочка таблицы -->
        <div class="suzipo-table-shell">
            @php
                $reqColGroup = '
                <colgroup>
                    <col style="width: 2%;">
                    <col style="width: 4%;">
                    <col style="width: 2.5%;">
                    <col style="width: 4.5%;">
                    <col style="width: 4.5%;"> <!-- дата оконч. -->
                    <col style="width: 19%;">
                    <col style="width: 12%;">
                    <col style="width: 8.5%;"> <!-- автор -->
                    <col style="width: 4.5%;">
                    <col style="width: 7.5%;">
                </colgroup>';
            @endphp

            <!-- 1. Шапка таблицы на сером фоне -->
            <table class="suzipo-table-head">
                {!! $reqColGroup !!}
                <thead>
                    <tr>
                        <th style="text-align: center;">
                            <input type="checkbox" id="select-all-requests" style="width: 15px; height: 15px; border-radius: 4px; cursor: pointer;">
                        </th>
                        <th>Статус</th>
                        <th>Разов.</th>
                        <th>Дата нач.</th>
                        <th>Дата окон.</th>
                        <th>Курс</th>
                        <th>Провайдер</th>
                        <th>Автор</th>
                        <th>Создана</th>
                        <th style="text-align: center;">Действия</th>
                    </tr>
                </thead>
            </table>
            <!-- 2. Внутренний блок строк -->
            <div class="suzipo-table-body-box">
                <table class="suzipo-table-body">
                    {!! $reqColGroup !!}
                    <tbody>
                        @forelse($requests as $request)
                        <tr style="text-align: center;">
                            <td style="text-align: center;">
                                @if(($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit') && $request->employees_count > 0)
                                    <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" form="mass-send-form" class="request-checkbox" style="width: 15px; height: 15px; border-radius: 4px; cursor: pointer;">
                                @else
                                    <input type="checkbox" disabled style="width: 15px; height: 15px; border-radius: 4px; opacity: 0.4; cursor: not-allowed;" title="{{ ($request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit') ? 'Заявка не в статусе Создана' : 'В заявке нет сотрудников' }}">
                                @endif
                            </td>
                            <!-- Статус -->
                            <td>
                                @php
                                    $st = $request->status;
                                    if ($st === 'Создана' || $st === 'created') {
                                        $badgeBg = 'rgba(245, 158, 11, 0.14)'; $badgeColor = '#D97706';
                                    } elseif ($st === 'urpedit') {
                                        $badgeBg = 'rgba(249, 115, 22, 0.14)'; $badgeColor = '#EA580C';
                                    } elseif ($st === 'Отправлена' || $st === 'sent') {
                                        $badgeBg = 'rgba(59, 130, 246, 0.14)'; $badgeColor = '#3B82F6';
                                    } elseif ($st === 'accepted') {
                                        $badgeBg = 'rgba(16, 185, 129, 0.14)'; $badgeColor = '#10B981';
                                    } elseif ($st === 'rejected') {
                                        $badgeBg = 'rgba(239, 68, 68, 0.14)'; $badgeColor = '#EF4444';
                                    } elseif ($st === 'in_progress') {
                                        $badgeBg = 'rgba(139, 92, 246, 0.14)'; $badgeColor = '#8B5CF6';
                                    } else {
                                        $badgeBg = 'var(--pill-btn-bg)'; $badgeColor = 'var(--table-row-text)';
                                    }
                                @endphp
                                <span style="display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 12px; font-size: 11.5px; font-weight: 500; background-color: {{ $badgeBg }}; color: {{ $badgeColor }};">
                                    {{ $request->status_label }}
                                </span>
                            </td>
                            <!-- Разов. -->
                            <td style="color: var(--table-row-text);">{{ $request->one_time ? 'Да' : 'Нет' }}</td>
                            <!-- Дата нач. -->
                            <td style="color: var(--table-row-text);">{{ $request->start_date ? $request->start_date->format('d.m') : '—' }}</td>
                            <!-- Дата окон. -->
                            <td style="color: var(--table-row-text);">{{ $request->end_date ? $request->end_date->format('d.m') : '—' }}</td>
                            <!-- Курс -->
                            <td style="font-weight: 500; color: var(--table-row-text); text-align:left" title="{{ $request->course->course ?? '' }}">
                                {{ $request->course->course ?? '—' }}
                            </td>
                            <!-- Провайдер -->
                            <td style="color: var(--table-row-text);" title="{{ $request->provider->name ?? '' }}">
                                {{ $request->provider->name ?? '—' }}
                            </td>
                            <!-- Автор -->
                            <td style="color: var(--table-row-text);" title="{{ $request->user->name ?? '' }}">
                                {{ $request->user->name ?? '—' }}
                            </td>
                            <!-- Создана -->
                            <td style="color: var(--table-row-text);">{{ $request->created_at->format('d.m.Y') }}</td>
                            <!-- Действия -->
                            <td style="text-align: center; overflow: visible;">
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                                    <!-- Одиночная отправка -->
                                    @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) && ($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit') && $request->employees_count > 0)
                                    <button type="button"
                                            class="suzipo-action-circle"
                                            style="color: #3B82F6;"
                                            title="Отправить одну заявку"
                                            data-id="{{ $request->id }}"
                                            onclick="sendSingleRequest(this)">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                    </button>
                                    @endif
                                    <!-- Посмотреть -->
                                    <a href="{{ route('requests.show', $request) }}" class="suzipo-action-circle" title="Посмотреть">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <!-- Редактировать -->
                                    @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) || ($request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit'))
                                    <a href="{{ route('requests.edit', $request) }}" class="suzipo-action-circle" title="Редактировать">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    @endif
                                    <!-- Сотрудники -->
                                    @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) || ($request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit'))
                                    <a href="{{ route('request-employees.index', $request->id) }}" class="suzipo-action-circle" style="position: relative;" title="Сотрудники">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        @if($request->employees_count > 0)
                                        <span style="position: absolute; top: -4px; right: -4px; background-color: #10B981; color: #FFFFFF; font-size: 9px; font-weight: 700; min-width: 15px; height: 15px; padding: 0 3px; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center;">
                                            {{ $request->employees_count }}
                                        </span>
                                        @endif
                                    </a>
                                    @endif
                                    <!-- Удалить -->
                                    @if(auth()->user()->hasRole('admin') ||
                                        (auth()->user()->hasAnyRole(['urp', 'urp admin']) && ($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit')) ||
                                        (auth()->user()->hasAnyRole(['ooo', 'ooo admin', 'ooo chief']) && $request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit'))
                                    <form action="{{ route('requests.destroy', $request) }}" method="POST" style="display: inline-flex; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="suzipo-action-circle" style="background-color: rgba(239, 68, 68, 0.14); color: #EF4444;" title="Удалить" onclick="return confirm('Удалить заявку?')">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" style="padding: 28px; text-align: center; color: var(--table-row-text); font-size: 13px;">
                                Заявок пока нет
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- 3. Пагинация -->
            @php
                $curPage   = $requests->currentPage();
                $lastPage  = $requests->lastPage();
                $perPage   = $requests->perPage();
                $totalRows = $requests->total();
                $firstItem = $requests->firstItem() ?? 0;

                $pages = collect([1, 2, 3, $curPage - 1, $curPage, $curPage + 1, $lastPage - 1, $lastPage])
                    ->filter(fn($p) => $p >= 1 && $p <= $lastPage)
                    ->unique()
                    ->sort()
                    ->values();
            @endphp
            <div style="display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 10px 8px 10px 16px; min-height: 56px; gap: 16px; font-size: 14px; color: var(--table-row-text); box-sizing: border-box;">
                @php
                    $allLimits = array(10, 25, 50, 100);
                    $availableLimits = array();
                    foreach ($allLimits as $idx => $lim) {
                        if ($idx === 0 || $totalRows > $allLimits[$idx - 1]) {
                            $availableLimits[] = $lim;
                        }
                    }
                @endphp
                <div style="display: flex; flex-direction: row; align-items: center; gap: 12px;" x-data="{ perPageOpen: false }">
                    <span>Показывать</span>
                    <div style="position: relative; display: inline-flex; align-items: center;">
                        <button type="button"
                                @click="perPageOpen = !perPageOpen"
                                @click.away="perPageOpen = false"
                                style="display: inline-flex; align-items: center; justify-content: space-between; gap: 8px; min-width: 83px; height: 36px; background-color: var(--table-body-bg); color: var(--table-row-text); border: none; border-radius: 8px; padding: 8px 12px; font-size: 14px; font-weight: 400; line-height: 20px; cursor: pointer; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.06), 0px 1px 2px rgba(0, 0, 0, 0.06), 0px 2px 4px rgba(0, 0, 0, 0.04);">
                            <span>{{ $totalRows > 0 ? $firstItem . '-' . $requests->lastItem() : '0' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" style="color: var(--table-row-text);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="perPageOpen" x-cloak
                             style="position: absolute; bottom: calc(100% + 6px); left: 0; min-width: 96px; padding: 4px; border-radius: 12px; background-color: var(--table-body-bg); border: 1px solid var(--border-color); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.14); z-index: 999;">
                            @foreach($availableLimits as $limit)
                                @php
                                    $rangeStart = $totalRows > 0 ? $firstItem : 1;
                                    $rangeEnd   = $totalRows > 0 ? min($firstItem + $limit - 1, $totalRows) : $limit;
                                @endphp
                                <button type="button"
                                        onclick="changeRequestsPerPage({{ $limit }})"
                                        style="width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 7px 12px; border: none; border-radius: 8px; font-size: 13px; text-align: left; cursor: pointer; {{ $perPage === $limit ? 'background-color: var(--pill-btn-bg); font-weight: 600; color: var(--table-row-text);' : 'background: transparent; color: var(--table-row-text);' }}">
                                    <span>{{ $totalRows > 0 ? $rangeStart . '-' . $rangeEnd : '0' }}</span>
                                    @if($perPage === $limit)
                                        <span style="color: #D97706;">✓</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <span>из {{ number_format($totalRows, 0, '', ' ') }}</span>
                </div>


                <!-- Правая часть: < Назад  1  2  3 ... 10  11  Вперёд > -->
                <div style="display: flex; flex-direction: row; align-items: center; gap: 8px;">
                    @if ($requests->onFirstPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); opacity: 0.5; cursor: not-allowed;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Назад
                        </span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); text-decoration: none;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Назад
                        </a>
                    @endif

                    <div style="display: flex; flex-direction: row; align-items: center; gap: 8px;">
                        @foreach($pages as $idx => $page)
                            @if($idx > 0 && $page - $pages[$idx - 1] > 1)
                                <span style="font-size: 14px; font-weight: 500; color: var(--table-row-text); padding: 0 2px;">...</span>
                            @endif

                            @if($page === $curPage)
                                <span style="width: 32px; min-width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; background-color: var(--pill-btn-bg); color: var(--table-row-text); font-size: 14px; font-weight: 500;">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $requests->url($page) }}" style="width: 32px; min-width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; color: var(--table-row-text); font-size: 14px; font-weight: 500; text-decoration: none;">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); text-decoration: none;">
                            Вперёд
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); opacity: 0.5; cursor: not-allowed;">
                            Вперёд
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeRequestsPerPage(limit) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', limit);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-requests');
            const rowCheckboxes = document.querySelectorAll('.request-checkbox');
            const massSendBtn = document.getElementById('btn-mass-send');

            function toggleMassButton() {
                if (!massSendBtn) return;
                const checkedCount = document.querySelectorAll('.request-checkbox:checked').length;
                massSendBtn.style.display = checkedCount > 0 ? 'inline-flex' : 'none';
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(cb => {
                        if (!cb.disabled) {
                            cb.checked = selectAllCheckbox.checked;
                        }
                    });
                    toggleMassButton();
                });
            }

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    toggleMassButton();
                    if (!this.checked && selectAllCheckbox) {
                        selectAllCheckbox.checked = false;
                    }
                });
            });
        });

        function sendSingleRequest(button) {
            const id = button.getAttribute('data-id');

            if (confirm('Отправить эту заявку в ООО?')) {
                const form = document.getElementById('mass-send-form');
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
