<x-layouts.app-with-sidebar>
    <div x-data="{ activeTab: 'training' }">
        <div class="dashboard-nav-grid">
            <!-- 1. Заявки на обучение -->
            <a href="{{ route('requests.index') }}" class="suzipo-card transition-all hover:opacity-90"
               style="position: relative; display: block; height: 104px; padding: 18px 20px; text-decoration: none; overflow: hidden;">
                <div class="font-semibold text-lg" style="color: var(--text-primary); position: relative; z-index: 2;">
                    Заявки на обучение
                </div>
                <img src="{{ asset('images/dashboard/requests.svg') }}" alt="Заявки на обучение"
                     style="position: absolute; right: 10px; bottom: -6px; height: 78px; width: auto; object-fit: contain; pointer-events: none; z-index: 1;"
                     onerror="this.style.display='none';">
            </a>

            <!-- 2. Протоколы -->
            <a href="{{ route('protocols.index') }}" class="suzipo-card transition-all hover:opacity-90"
               style="position: relative; display: block; height: 104px; padding: 18px 20px; text-decoration: none; overflow: hidden;">
                <div class="font-semibold text-lg" style="color: var(--text-primary); position: relative; z-index: 2;">
                    Протоколы
                </div>
                <img src="{{ asset('images/dashboard/protocols.svg') }}" alt="Протоколы"
                     style="position: absolute; right: 10px; bottom: -6px; height: 78px; width: auto; object-fit: contain; pointer-events: none; z-index: 1;"
                     onerror="this.style.display='none';">
            </a>

            <!-- 3. Справочники -->
            <a href="{{ route('directories.courses.index') }}" class="suzipo-card transition-all hover:opacity-90"
               style="position: relative; display: block; height: 104px; padding: 18px 20px; text-decoration: none; overflow: hidden;">
                <div class="font-semibold text-lg" style="color: var(--text-primary); position: relative; z-index: 2;">
                    Справочники
                </div>
                <img src="{{ asset('images/dashboard/directories.svg') }}" alt="Справочники"
                     style="position: absolute; right: 10px; bottom: -6px; height: 78px; width: auto; object-fit: contain; pointer-events: none; z-index: 1;"
                     onerror="this.style.display='none';">
            </a>

            <!-- 4. Отчёты -->
            <a href="{{ route('reports.training-journal') }}" class="suzipo-card transition-all hover:opacity-90"
               style="position: relative; display: block; height: 104px; padding: 18px 20px; text-decoration: none; overflow: hidden;">
                <div class="font-semibold text-lg" style="color: var(--text-primary); position: relative; z-index: 2;">
                    Отчёты
                </div>
                <img src="{{ asset('images/dashboard/reports.svg') }}" alt="Отчёты"
                     style="position: absolute; right: 10px; bottom: -6px; height: 78px; width: auto; object-fit: contain; pointer-events: none; z-index: 1;"
                     onerror="this.style.display='none';">
            </a>

            <!-- 5. Программы (Матрицы) -->
            <a href="{{ route('matrices.index') }}" class="suzipo-card transition-all hover:opacity-90"
               style="position: relative; display: block; height: 104px; padding: 18px 20px; text-decoration: none; overflow: hidden;">
                <div class="font-semibold text-lg" style="color: var(--text-primary); position: relative; z-index: 2;">
                    Программы
                </div>
                <img src="{{ asset('images/dashboard/programs.svg') }}" alt="Программы"
                     style="position: absolute; right: 10px; bottom: -6px; height: 78px; width: auto; object-fit: contain; pointer-events: none; z-index: 1;"
                     onerror="this.style.display='none';">
            </a>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 mb-4 mt-2">
            <h2 class="text-lg font-bold" style="color: var(--text-primary);">Обзор обучения</h2>

            <!-- Переключатель вкладок -->
            <div class="inline-flex p-1 rounded-full border"
                 style="background-color: var(--bg-surface); border-color: var(--border-color);">
                <button type="button" @click="activeTab = 'training'"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors"
                        :style="activeTab === 'training' ? 'background-color: var(--bg-page); color: var(--text-primary); box-shadow: 0 1px 2px rgba(0,0,0,0.06);' : 'color: var(--text-secondary);'">
                    Обучение
                </button>
                <button type="button" @click="activeTab = 'requests'"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors"
                        :style="activeTab === 'requests' ? 'background-color: var(--bg-page); color: var(--text-primary); box-shadow: 0 1px 2px rgba(0,0,0,0.06);' : 'color: var(--text-secondary);'">
                    Последние заявки
                </button>
            </div>
        </div>

        <!-- Основная двухколоночная сетка -->
        <div class="dashboard-main-grid" style="align-items: stretch;">
            <div class="flex flex-col gap-3">
                <div class="kpi-grid-2x2" style="margin-bottom: 0;">
                    <div class="suzipo-card p-4" style="border: none;">
                        <div class="text-xs font-medium mb-1" style="color: var(--text-secondary);">Сотрудников</div>
                        <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ number_format($kpi['employees'] ?? 0, 0, '', ' ') }}</div>
                    </div>

                    <div class="suzipo-card p-4" style="border: none;">
                        <div class="text-xs font-medium mb-1" style="color: var(--text-secondary);">На контроле</div>
                        <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ number_format($kpi['control'] ?? 0, 0, '', ' ') }}</div>
                    </div>

                    <div class="suzipo-card p-4" style="border: none;">
                        <div class="text-xs font-medium mb-1" style="color: var(--text-secondary);">Истекает</div>
                        <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ number_format($kpi['expiring'] ?? 0, 0, '', ' ') }}</div>
                    </div>

                    <div class="suzipo-card p-4" style="border: none;">
                        <div class="text-xs font-medium mb-1" style="color: var(--text-secondary);">Просрочено</div>
                        <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ number_format($kpi['overdue'] ?? 0, 0, '', ' ') }}</div>
                    </div>
                </div>

                <!-- Отдельная серая карточка кольцевой диаграммы -->
                @php
                    $actualPct    = (int)($chartData['actual'] ?? 0);
                    $expiringPct  = (int)($chartData['expiring'] ?? 0);
                    $overduePct   = (int)($chartData['overdue'] ?? 0);
                    $plannedPct   = (int)($chartData['planned'] ?? 0);
                    $untrainedPct = (int)($chartData['untrained'] ?? 0);

                    $c = 251.2;
                    $dashActual    = ($actualPct / 100) * $c;
                    $dashExpiring  = ($expiringPct / 100) * $c;
                    $dashOverdue   = ($overduePct / 100) * $c;
                    $dashPlanned   = ($plannedPct / 100) * $c;
                    $dashUntrained = ($untrainedPct / 100) * $c;

                    $offExpiring  = -$dashActual;
                    $offOverdue   = -($dashActual + $dashExpiring);
                    $offPlanned   = -($dashActual + $dashExpiring + $dashOverdue);
                    $offUntrained = -($dashActual + $dashExpiring + $dashOverdue + $dashPlanned);
                @endphp

                <div class="suzipo-card p-5 flex-1 flex flex-col justify-between" style="border: none;">
                    <div class="text-sm font-bold text-center mb-3" style="color: var(--text-primary);">Соответствие обучению</div>

                    <div class="flex items-center justify-center my-3 relative">
                        <svg width="165" height="165" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="var(--border-color)" stroke-width="10" />
                            @if($actualPct > 0)
                                <circle cx="50" cy="50" r="40" fill="transparent" stroke="#10B981" stroke-width="10"
                                        stroke-dasharray="{{ $dashActual }} {{ $c }}" stroke-dashoffset="0" />
                            @endif
                            @if($expiringPct > 0)
                                <circle cx="50" cy="50" r="40" fill="transparent" stroke="#F59E0B" stroke-width="10"
                                        stroke-dasharray="{{ $dashExpiring }} {{ $c }}" stroke-dashoffset="{{ $offExpiring }}" />
                            @endif
                            @if($overduePct > 0)
                                <circle cx="50" cy="50" r="40" fill="transparent" stroke="#EF4444" stroke-width="10"
                                        stroke-dasharray="{{ $dashOverdue }} {{ $c }}" stroke-dashoffset="{{ $offOverdue }}" />
                            @endif
                            @if($plannedPct > 0)
                                <circle cx="50" cy="50" r="40" fill="transparent" stroke="#3B82F6" stroke-width="10"
                                        stroke-dasharray="{{ $dashPlanned }} {{ $c }}" stroke-dashoffset="{{ $offPlanned }}" />
                            @endif
                            @if($untrainedPct > 0)
                                <circle cx="50" cy="50" r="40" fill="transparent" stroke="#6B7280" stroke-width="10"
                                        stroke-dasharray="{{ $dashUntrained }} {{ $c }}" stroke-dashoffset="{{ $offUntrained }}" />
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-[11px]" style="color: var(--text-secondary);">Актуально</span>
                            <span class="text-2xl font-bold" style="color: var(--text-primary);">{{ $actualPct }}%</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5 mt-4 text-[11px]">
                        <span class="inline-flex items-center gap-1.5" style="color: var(--text-secondary);">
                            <span class="w-2 h-2 rounded-full" style="background-color: #10B981;"></span>Актуально ({{ $actualPct }}%)
                        </span>
                        <span class="inline-flex items-center gap-1.5" style="color: var(--text-secondary);">
                            <span class="w-2 h-2 rounded-full" style="background-color: #F59E0B;"></span>Истекает ({{ $expiringPct }}%)
                        </span>
                        <span class="inline-flex items-center gap-1.5" style="color: var(--text-secondary);">
                            <span class="w-2 h-2 rounded-full" style="background-color: #EF4444;"></span>Просрочено ({{ $overduePct }}%)
                        </span>
                        <span class="inline-flex items-center gap-1.5" style="color: var(--text-secondary);">
                            <span class="w-2 h-2 rounded-full" style="background-color: #3B82F6;"></span>Плановая ({{ $plannedPct }}%)
                        </span>
                        <span class="inline-flex items-center gap-1.5" style="color: var(--text-secondary);">
                            <span class="w-2 h-2 rounded-full" style="background-color: #6B7280;"></span>Не обучено ({{ $untrainedPct }}%)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Правая колонка: Оперативная лента -->
            <div class="suzipo-card px-6 py-2 flex flex-col justify-between">
                <!-- Вкладка 1: Обучение -->
                <div x-show="activeTab === 'training'" class="divide-y" style="border-color: var(--border-color);">
                    @forelse(($recentTraining ?? collect()) as $item)
                        @php
                            $markRaw = mb_strtolower(trim((string)($item->markitog ?? '')));
                            $isPassed = ((int)($item->flagpassed ?? 0) === 1) || in_array($markRaw, ['зачет', 'зачтено', 'удовл', 'удовлетворительно', 'хорошо', 'отлично', '3', '4', '5']);
                            $isNoShow = str_contains($markRaw, 'неяв');

                            $dotColor = $isPassed ? '#10B981' : ($isNoShow ? '#EF4444' : '#F59E0B');
                            $statusLabel = $isPassed ? 'Актуально' : ($isNoShow ? 'Просрочено' : 'На контроле');
                        @endphp
                        <div class="py-3.5 grid items-center gap-4 border-b last:border-b-0 text-xs"
                             style="grid-template-columns: 130px 1fr 1fr 90px; border-color: var(--border-color);">
                            <span class="inline-flex items-center gap-2 font-medium" style="color: var(--text-primary);">
                                <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $dotColor }};"></span>
                                {{ $statusLabel }}
                            </span>
                            <span class="font-medium truncate" style="color: var(--text-primary);">
                                {{ $item->fiofact ?? 'Слушатель #' . $item->id_students }}
                            </span>
                            <span class="truncate" style="color: var(--text-secondary);">
                                Протокол № {{ $item->id_protocol ?? '—' }}
                            </span>
                            <div class="text-right">
                                @if(!empty($item->id_protocol))
                                    <a href="{{ route('protocols.report.create', $item->id_protocol) }}"
                                       class="font-medium hover:underline" style="color: var(--text-secondary);">
                                        Открыть
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-sm" style="color: var(--text-secondary);">
                            Записей об обучении пока нет.
                        </div>
                    @endforelse
                </div>

                <!-- Вкладка 2: Последние заявки -->
                <div x-show="activeTab === 'requests'" x-cloak>
                    @forelse(($recentRequests ?? collect()) as $req)
                        @php
                            $st = $req->status ?? 'created';
                            $statusMap = [
                                'created'     => ['label' => 'Создана',       'color' => '#6B7280'],
                                'sent'        => ['label' => 'Отправлена',    'color' => '#3B82F6'],
                                'in_progress' => ['label' => 'В работе',      'color' => '#F59E0B'],
                                'urpedit'     => ['label' => 'На доработке',  'color' => '#EF4444'],
                                'accepted'    => ['label' => 'Принята',       'color' => '#10B981'],
                                'rejected'    => ['label' => 'Отклонена',     'color' => '#EF4444'],
                            ];
                            $stInfo = $statusMap[$st] ?? ['label' => $st, 'color' => '#6B7280'];
                        @endphp
                        <div class="py-3.5 grid items-center gap-4 border-b last:border-b-0 text-xs"
                             style="grid-template-columns: 130px 1fr 1fr 90px; border-color: var(--border-color);">
                            <span class="inline-flex items-center gap-2 font-medium" style="color: var(--text-primary);">
                                <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $stInfo['color'] }};"></span>
                                {{ $stInfo['label'] }}
                            </span>
                            <span class="font-medium truncate" style="color: var(--text-primary);">
                                Заявка № {{ $req->number ?? $req->id }}
                            </span>
                            <span class="truncate" style="color: var(--text-secondary);">
                                {{ !empty($req->created_at) ? \Carbon\Carbon::parse($req->created_at)->format('d.m.Y') : '—' }}
                            </span>
                            <div class="text-right">
                                <a href="{{ route('requests.show', $req->id) }}"
                                   class="font-medium hover:underline" style="color: var(--text-secondary);">
                                    Перейти
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-sm" style="color: var(--text-secondary);">
                            Заявок пока нет.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts.app-with-sidebar>