<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;" x-data="protocolsComponent()">

        <!-- Заголовок страницы -->
        <div style="margin-bottom: 16px;">
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0;">Реестр протоколов обучения</h1>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="padding: 12px 16px; background-color: rgba(16, 185, 129, 0.12); border: 1px solid #10B981; color: #10B981; border-radius: 10px; font-size: 13px; margin-bottom: 20px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- Панель вкладок и кнопка выгрузки (строго НАД таблицей в одну строку) -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 1px solid var(--border-color); margin-bottom: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 22px; flex-wrap: wrap;">
                <span @click="switchTab('all')" class="suzipo-tab-item" :class="{ 'active': activeTab === 'all' }">
                    Все протоколы <span style="font-size: 11px; color: var(--text-secondary);">({{ $totalCount ?? 0 }})</span>
                </span>
                <span @click="switchTab('1')" class="suzipo-tab-item" :class="{ 'active': activeTab === '1' }">
                    Активные <span style="font-size: 11px; color: var(--text-secondary);">({{ \Illuminate\Support\Arr::get($counts ?? [], 1, 0) }})</span>
                </span>
                <span @click="switchTab('0')" class="suzipo-tab-item" :class="{ 'active': activeTab === '0' }">
                    Черновики <span style="font-size: 11px; color: var(--text-secondary);">({{ \Illuminate\Support\Arr::get($counts ?? [], 0, 0) }})</span>
                </span>
                <span @click="switchTab('4')" class="suzipo-tab-item" :class="{ 'active': activeTab === '4' }">
                    Завершённые <span style="font-size: 11px; color: var(--text-secondary);">({{ \Illuminate\Support\Arr::get($counts ?? [], 4, 0) }})</span>
                </span>
                <span @click="switchTab('2')" class="suzipo-tab-item" :class="{ 'active': activeTab === '2' }">
                    Просроченные <span style="font-size: 11px; color: var(--text-secondary);">({{ \Illuminate\Support\Arr::get($counts ?? [], 2, 0) }})</span>
                </span>
                <span @click="switchTab('3')" class="suzipo-tab-item" :class="{ 'active': activeTab === '3' }">
                    Отменённые <span style="font-size: 11px; color: var(--text-secondary);">({{ \Illuminate\Support\Arr::get($counts ?? [], 3, 0) }})</span>
                </span>
            </div>

            <button type="button" @click="isDownloadModalOpen = true"
                    style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 10px; font-size: 12px; font-weight: 500; color: var(--text-primary); cursor: pointer; margin-bottom: 8px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Загрузить отчёт из БД
            </button>
        </div>
        <!-- Модальное окно выгрузки отчёта из БД -->
        <div x-show="isDownloadModalOpen"
            style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.55); z-index: 99999;"
            x-cloak>
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: var(--bg-page); border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25); width: 100%; max-width: 520px; border: 1px solid var(--border-color); display: flex; flex-direction: column; font-size: 13px; color: var(--text-primary); box-sizing: border-box; overflow: hidden;">
                <div style="padding: 16px 20px; background-color: var(--bg-surface); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; box-sizing: border-box;">
                    <span style="font-weight: 700; font-size: 15px; color: var(--text-primary);">Выгрузка отчета из БД</span>
                    <button type="button" @click="isDownloadModalOpen = false" style="margin-left: auto; border: none; background: none; font-size: 22px; color: var(--text-secondary); cursor: pointer; line-height: 1; padding: 0;">&times;</button>
                </div>
                <form action="{{ route('protocols.export-excel', '') }}" method="GET" style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                    <input type="hidden" name="protocol_id" value="{{ $protocol->prot_id ?? $protocol->id ?? '' }}">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary); font-size: 12px;">Тип формируемого отчета:</label>
                        <div style="display: flex; gap: 16px; background: var(--bg-surface); padding: 10px 14px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--text-primary);">
                                <input type="radio" name="report_type" value="standard" x-model="reportType" checked style="margin:0;"> Протокол
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--text-primary);">
                                <input type="radio" name="report_type" value="eisot" x-model="reportType" style="margin:0;"> Для ЕИСОТ
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--text-primary);">
                                <input type="radio" name="report_type" value="multimedia" x-model="reportType" style="margin:0;"> Мультимедиа
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px; border: 1px solid var(--border-color); border-radius: 12px; padding: 14px; background-color: var(--bg-surface);">
                        <span style="display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Критерии фильтрации протоколов</span>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <label class="suzipo-label">Состояние</label>
                                <select x-model="filterStatus" @change="protocolId = ''" class="suzipo-field" style="padding: 8px 10px !important; background-color: var(--bg-page) !important;">
                                    <option value="1">В работе</option>
                                    <option value="4">Утвержден / Завершен</option>
                                </select>
                            </div>
                            <div>
                                <label class="suzipo-label">Вид обучения</label>
                                <select x-model="filterType" @change="protocolId = ''" class="suzipo-field" style="padding: 8px 10px !important; background-color: var(--bg-page) !important;">
                                    <option value="">Все виды обучения</option>
                                    @foreach($popupLearningTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="suzipo-label">Преподаватель курса</label>
                            <select x-model="filterTeacher" @change="protocolId = ''" class="suzipo-field" style="padding: 8px 10px !important; background-color: var(--bg-page) !important;">
                                <option value="">Все преподаватели</option>
                                @foreach($popupTeachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->fio }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="suzipo-label" style="color: var(--text-primary); font-weight: 600;">Выберите итоговый протокол: <span style="color: #EF4444;">*</span></label>
                        <select name="protocol_id" x-model="protocolId" required class="suzipo-field">
                            <option value="">— Нажмите для выбора записи —</option>
                            <template x-for="p in filteredProtocols" :key="p.prot_id">
                                <option :value="p.prot_id" x-text="`Протокол № ${p.prot_num} (ID: ${p.prot_id})`"></option>
                            </template>
                        </select>

                        <div x-show="filteredProtocols.length === 0" style="font-size: 12px; color: #EF4444; margin-top: 8px; text-align: center; font-weight: 500; padding: 8px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
                            ⚠️ Записи с такими параметрами отбора не найдены
                        </div>
                    </div>

                    <div style="margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="button" @click="isDownloadModalOpen = false" class="btn-suzipo-cancel">Отмена</button>
                        <button type="submit" :disabled="!protocolId" class="btn-suzipo-save" :style="!protocolId ? 'opacity: 0.5; cursor: not-allowed;' : ''">Загрузить отчет</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Адаптивная таблица реестра протоколов -->
        <div class="suzipo-table-shell">
            @php
                $colGroupHtml = '
                <colgroup>
                    <col style="width: 7.5%;">
                    <col style="width: 5.5%;">
                    <col style="width: 6.5%;">
                    <col style="width: 18%;">
                    <col style="width: 6.5%;">
                    <col style="width: 6.5%;">
                    <col style="width: 10.5%;">
                    <col style="width: 5.5%;">
                    <col style="width: 6.5%;">
                    <col style="width: 7.5%;">
                    <col style="width: 9%;">
                    <col style="width: 4%;">
                    <col style="width: 9%;">
                    <col style="width: 7.5%;">
                </colgroup>';
            @endphp

            <!-- 1. Шапка таблицы на сером фоне -->
            <table class="suzipo-table-head">
                {!! $colGroupHtml !!}
                <thead>
                    <tr>
                        <th>Состояние</th>
                        <th>№ Рег.</th>
                        <th>Создан</th>
                        <th>Курс / Квалификация</th>
                        <th>Начало</th>
                        <th>Окончание</th>
                        <th>Создатель / Редактор</th>
                        <th>№ Заяв.</th>
                        <th>Дата Заяв.</th>
                        <th>Квалификация</th>
                        <th>Преподаватель</th>
                        <th style="text-align: center;">Ауд.</th>
                        <th>Куратор</th>
                        <th style="text-align: right; padding-right: 14px;">Действия</th>
                    </tr>
                    <tr>
                        @include('protocols.partials.search-filters')
                    </tr>
                </thead>
            </table>

            <!-- 2. Внутренний белый скруглённый блок строк (bodySlot1: border-radius 12px) -->
            <div class="suzipo-table-body-box">
                <table class="suzipo-table-body">
                    {!! $colGroupHtml !!}
                    <tbody id="table-body">
                        @include('protocols.partials.table-rows')
                    </tbody>
                </table>
            </div>

        @php
            $curPage   = $protocols->currentPage();
            $lastPage  = $protocols->lastPage();
            $perPage   = $protocols->perPage();
            $totalRows = $protocols->total();
            $firstItem = $protocols->firstItem() ?? 0;
            $lastItem  = $protocols->lastItem() ?? 0;

            $pages = collect([1, 2, 3, $curPage - 1, $curPage, $curPage + 1, $lastPage - 1, $lastPage])
                ->filter(fn($p) => $p >= 1 && $p <= $lastPage)
                ->unique()
                ->sort()
                ->values();
        @endphp
        <div id="pagination-container" style="display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 10px 8px 10px 16px; min-height: 56px; gap: 16px; font-size: 14px; color: var(--table-head-text); box-sizing: border-box;">
                <!-- Левая часть: Показывать [InputGroup 83x36] из 120 -->
                <div style="display: flex; flex-direction: row; align-items: center; gap: 12px;">
                    <span>Показывать</span>
                    <div style="position: relative; width: 83px; height: 36px; display: inline-flex; align-items: center;">
                        <select onchange="changePerPage(this.value)"
                                style="appearance: none; -webkit-appearance: none; width: 83px; height: 36px; background-color: var(--table-body-bg); color: var(--table-row-text); border: none; border-radius: 8px; padding: 8px 28px 8px 12px; font-size: 14px; font-weight: 400; line-height: 20px; cursor: pointer; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.06), 0px 1px 2px rgba(0, 0, 0, 0.06), 0px 2px 4px rgba(0, 0, 0, 0.04);">
                            @foreach([10, 25, 50, 100] as $limit)
                                @php
                                    $rangeStart = $totalRows > 0 ? $firstItem : 1;
                                    $rangeEnd   = $totalRows > 0 ? min($firstItem + $limit - 1, $totalRows) : $limit;
                                @endphp
                                <option value="{{ $limit }}" {{ $perPage === $limit ? 'selected' : '' }}>
                                    {{ $rangeStart }}-{{ $rangeEnd }}
                                </option>
                            @endforeach
                        </select>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"
                             style="position: absolute; right: 10px; pointer-events: none; color: var(--table-row-text);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <span>из {{ number_format($totalRows, 0, '', ' ') }}</span>
                </div>

                <!-- Правая часть: < Назад  1  2  3 ... 10  11  Вперёд > -->
                <div style="display: flex; flex-direction: row; align-items: center; gap: 8px;">
                    @if ($protocols->onFirstPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); opacity: 0.5; cursor: not-allowed;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Назад
                        </span>
                    @else
                        <a href="{{ $protocols->previousPageUrl() }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); text-decoration: none;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Назад
                        </a>
                    @endif

                    <div style="display: flex; flex-direction: row; align-items: center; gap: 8px;">
                        @foreach($pages as $idx => $page)
                            @if($idx > 0 && $page - $pages[$idx - 1] > 1)
                                <span style="font-size: 14px; font-weight: 500; color: var(--table-head-text); padding: 0 2px;">...</span>
                            @endif

                            @if($page === $curPage)
                                <span style="width: 32px; min-width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; background-color: var(--pill-btn-bg); color: var(--table-row-text); font-size: 14px; font-weight: 500;">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $protocols->url($page) }}" style="width: 32px; min-width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; color: var(--table-row-text); font-size: 14px; font-weight: 500; text-decoration: none;">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    @if ($protocols->hasMorePages())
                        <a href="{{ $protocols->nextPageUrl() }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; height: 32px; padding: 6px 12px; border-radius: 16px; font-size: 14px; font-weight: 500; color: var(--table-row-text); text-decoration: none;">
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

        @include('protocols.partials.edit-modal')
    </div>
</x-layouts.app-with-sidebar>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('protocolsComponent', () => ({
            isEditOpen: false,
            isDownloadModalOpen: false,
            editData: {},
            activeTab: 'all',
            searchId: '',
            searchStatus: '',
            searchProtNum: '',
            searchCourse: '',
            searchEditor: '',
            searchDemNum: '',
            searchQual: '',
            searchTeacher: '',
            searchClassroom: '',
            searchCurator: '',
            filterTeacher: '',
            filterType: '',
            filterStatus: '1',
            reportType: 'standard',
            protocolId: '',
            isCreateReportOpen: false,
            activeReportTab: 'report',
            reportCreateData: {},

            openEdit(id) {
                fetch(`/protocols/${id}/json`)
                    .then(response => {
                        if (!response.ok) throw new Error('Ошибка при загрузке протокола');
                        return response.json();
                    })
                    .then(data => {
                        this.editData = data;
                        this.isEditOpen = true;
                    })
                    .catch(error => {
                        console.error('Ошибка AJAX:', error);
                        alert('Не удалось загрузить данные протокола.');
                    });
            },

            allProtocols: @json($jsonProtocols ?? []),

            get filteredProtocols() {
                return this.allProtocols.filter(p => {
                    if (this.filterStatus && p.prot_status != this.filterStatus) return false;
                    if (this.filterTeacher && p.teacher_id != this.filterTeacher) return false;
                    if (this.filterType && p.learning_type_id != this.filterType) return false;
                    return true;
                });
            },

            switchTab(tabId) {
                this.activeTab = tabId;
                this.fetchData();
            },

            fetchData() {
                let params = new URLSearchParams({
                    activeTab: this.activeTab,
                    searchId: this.searchId || '',
                    searchStatus: this.searchStatus || '',
                    searchProtNum: this.searchProtNum || '',
                    searchCourse: this.searchCourse || '',
                    searchEditor: this.searchEditor || '',
                    searchDemNum: this.searchDemNum || '',
                    searchQual: this.searchQual || '',
                    searchTeacher: this.searchTeacher || '',
                    searchClassroom: this.searchClassroom || '',
                    searchCurator: this.searchCurator || ''
                });

                fetch(`${window.location.pathname}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        let newBody = doc.getElementById('table-body');
                        if (newBody) {
                            document.getElementById('table-body').innerHTML = newBody.innerHTML;
                        } else {
                            document.getElementById('table-body').innerHTML = html;
                        }
                    })
                    .catch(error => console.error('Ошибка фильтрации:', error));
            }
        }));
    });

    function changePerPage(limit) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', limit);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
