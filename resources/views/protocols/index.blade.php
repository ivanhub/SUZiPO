<x-layouts.app-with-sidebar>
    <div class="max-w-full mx-auto p-6" style="font-family: system-ui, -apple-system, sans-serif; background-color: #fafafa; min-height: 100vh;" x-data="protocolsComponent()">

        <!-- Верхний заголовок системы как на скрине -->
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">Реестр протоколов обучения</h1>
            <p style="font-size: 13px; color: #6b7280; margin: 0;">Система управления заявками и протоколами обучения (СУЗиПО)</p>
        </div>

        <!-- Горизонтальные табы-переключатели со счетчиками -->
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; font-size: 13px; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; overflow-x: auto; white-space: nowrap;">
            <span @click="switchTab('all')"
                :style="activeTab === 'all' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Все протоколы
                <b style="background: #e5e7eb; color: #1f2937; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $totalCount }}</b>
            </span>

            <span @click="switchTab('1')"
                :style="activeTab === '1' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Активные
                <b style="background: #d1fae5; color: #065f46; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[1] ?? 0 }}</b>
            </span>

            <span @click="switchTab('0')"
                :style="activeTab === '0' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Черновики
                <b style="background: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[0] ?? 0 }}</b>
            </span>

            <span @click="switchTab('4')"
                :style="activeTab === '4' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Завершенные
                <b style="background: #e5e7eb; color: #1f2937; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[4] ?? 0 }}</b>
            </span>

            <span @click="switchTab('2')"
                :style="activeTab === '2' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Просроченные
                <b style="background: #e5e7eb; color: #1f2937; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[2] ?? 0 }}</b>
            </span>

            <span @click="switchTab('3')"
                :style="activeTab === '3' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Отмененные
                <b style="background: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[3] ?? 0 }}</b>
            </span>

            <!-- Кнопка вызова диалогового окна -->
            <button type="button" @click="isDownloadModalOpen = true" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-weight: 500; color: #374151; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                📥 Загрузить отчет из БД
            </button>

            <!-- ДИАЛОГОВОЕ ОКНО -->
                        
            <div x-show="isDownloadModalOpen"
                style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.3); z-index: 99999;"
                x-cloak>

                <!-- Белая карточка поп-апа с жесткими координатами центра -->
                <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #ffffff; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); width: 100%; max-width: 520px; border: 1px solid #e5e7eb; display: flex; flex-direction: column; font-family: system-ui, -apple-system, sans-serif; font-size: 13px; color: #1f2937; box-sizing: border-box; overflow: hidden;">
                    <!-- Шапка окна -->
                    <div style="padding: 14px 20px; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; box-sizing: border-box;">
                        <span style="font-weight: 700; font-size: 15px; color: #111827;">Выгрузка отчета из БД</span>
                        <button type="button" @click="isDownloadModalOpen = false" style="margin-left: auto; border: none; background: none; font-size: 22px; color: #9ca3af; cursor: pointer; line-height: 1; padding: 0;" onmouseover="this.style.color='#4b5563'" onmouseout="this.style.color='#9ca3af'">&times;</button>
                    </div>

                    <!-- Форма отправки параметров на бэкенд -->
                    <form action="{{ route('protocols.export-excel') }}" method="POST" style="padding: 20px; display: flex; flex-direction: column; gap: 16px; margin: 0; box-sizing: border-box;">
                        @csrf

                        <!-- Блок настроек типа Excel-файла (Радио-кнопки) -->
                        <div>
                            <label style="display: block; text-xs font-medium text-gray-700 mb-2; font-weight: 600; color: #374151;">Тип формируемого отчета:</label>
                            <div style="display: flex; gap: 16px; background: #f9fafb; padding: 10px 12px; border-radius: 6px; border: 1px solid #e5e7eb;">
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: #4b5563;">
                                    <input type="radio" name="report_type" value="standard" x-model="reportType" checked style="margin:0; text-indigo-600"> Протокол
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: #4b5563;">
                                    <input type="radio" name="report_type" value="eisot" x-model="reportType" style="margin:0; text-indigo-600"> Для ЕИСОТ
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: #4b5563;">
                                    <input type="radio" name="report_type" value="multimedia" x-model="reportType" style="margin:0; text-indigo-600"> Мультимедиа
                                </label>
                            </div>
                        </div>

                        <!-- Контейнер блоков фильтрации отбора -->
                        <div style="display: flex; flex-direction: column; gap: 12px; border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px; background-color: #fafafa;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Критерии фильтрации протоколов</span>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <label style="display:block; font-size: 11px; color:#6b7280; margin-bottom:4px; font-weight: 500;">Состояние</label>
                                    <select x-model="filterStatus" @change="protocolId = ''" style="width: 100%; padding: 6px 10px; font-size: 12px; color:#111827; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff;">
                                        <option value="1">В работе</option>
                                        <option value="4">Утвержден / Завершен</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block; font-size: 11px; color:#6b7280; margin-bottom:4px; font-weight: 500;">Вид обучения</label>
                                    <select x-model="filterType" @change="protocolId = ''" style="width: 100%; padding: 3px; padding: 6px 10px; font-size: 12px; color:#111827; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff;">
                                        <option value="">Все виды обучения</option>
                                        @foreach($popupLearningTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label style="display:block; font-size: 11px; color:#6b7280; margin-bottom:4px; font-weight: 500;">Преподаватель курса</label>
                                <select x-model="filterTeacher" @change="protocolId = ''" style="width: 100%; padding: 6px 10px; font-size: 12px; color:#111827; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff;">
                                    <option value="">Все преподаватели</option>
                                    @foreach($popupTeachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->fio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Динамический выпадающий список отфильтрованных протоколов -->
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">Выберите итоговый протокол: <span style="color: #dc2626;">*</span></label>
                            <select name="protocol_id" x-model="protocolId" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; background: #ffffff; color: #111827; font-size: 13px; border-radius: 6px; font-weight: 500; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                <option value="">— Нажмите для выбора записи —</option>
                                <template x-for="p in filteredProtocols" :key="p.prot_id">
                                    <option :value="p.prot_id" x-text="`Протокол № ${p.prot_num} (ID: ${p.prot_id})`"></option>
                                </template>
                            </select>

                            <!-- Сообщение, если по выбранным фильтрам ничего не найдено -->
                            <div x-show="filteredProtocols.length === 0" style="font-size: 12px; color: #dc2626; margin-top: 8px; text-align: center; font-weight: 500; padding: 6px; background: #fee2e2; border-radius: 6px;">
                                ⚠️ Записи с такими параметрами отбора не найдены
                            </div>
                        </div>

                        <!-- Нижняя панель с кнопками управления -->
                        <div style="margin-top: 12px; padding-top: 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="button" @click="isDownloadModalOpen = false" style="padding: 7px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: #ffffff; font-weight: 500; cursor: pointer; color: #374151;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='#ffffff'">Отмена</button>
                            <button type="submit" :disabled="!protocolId" style="padding: 7px 16px; border: none; border-radius: 6px; background: #3b82f6; color: #ffffff; font-weight: 500; cursor: pointer;" :style="!protocolId ? 'opacity: 0.5; cursor: not-allowed;' : ''" onmouseover="if(protocolId) this.style.backgroundColor='#2563eb'" onmouseout="if(protocolId) this.style.backgroundColor='#3b82f6'">Загрузить отчет</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Табличный блок реестра -->
        <div style="overflow-x: auto; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); box-sizing: border-box; margin-top: 20px;">
            <table style="table-layout: fixed; width: 100%; min-width: 1450px; border-collapse: collapse; font-size: 12px; color: #374151;">
                <thead>
                    <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb; color: #7c8594; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">
                        <th width="75" style="padding: 12px 8px; text-align: center;"></th>
                        <th width="65" style="padding: 12px 8px; text-align: left;">ID</th>
                        <th width="100" style="padding: 12px 8px; text-align: left;">Состояние</th>
                        <th width="85" style="padding: 12px 8px; text-align: left;">№ Рег.</th>
                        <th width="105" style="padding: 12px 8px; text-align: left;">Создан</th>
                        <th style="padding: 12px 8px; text-align: left;">Курс / Квалификация</th>
                        <th width="110" style="padding: 12px 8px; text-align: left;">Начало</th>
                        <th width="110" style="padding: 12px 8px; text-align: left;">Окончание</th>
                        <th width="160" style="padding: 12px 8px; text-align: left;">Создатель / Редактор</th>
                        <th width="85" style="padding: 12px 8px; text-align: left;">№ Заяв.</th>
                        <th width="105" style="padding: 12px 8px; text-align: left;">Дата Заяв.</th>
                        <th width="110" style="padding: 12px 8px; text-align: left;">Квалификация</th>
                        <th width="150" style="padding: 12px 8px; text-align: left;">Преподаватель</th>
                        <th width="85" style="padding: 12px 8px; text-align: center;">Ауд.</th>
                        <th width="130" style="padding: 12px 8px; text-align: left;">Куратор</th>
                    </tr>
                    <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">
                        @include('protocols.partials.search-filters')
                    </tr>
                </thead>
                <tbody id="table-body" style="background-color: #ffffff;">
                    @include('protocols.partials.table-rows')
                </tbody>
            </table>
        </div>

        <!-- Блок постраничной навигации -->
        <div id="pagination-container" style="margin-top: 15px; display: flex; justify-content: flex-end; align-items: center; gap: 8px; font-family: Arial, sans-serif; font-size: 12px;">
            @if ($protocols->onFirstPage())
            <span style="padding: 5px 10px; border: 1px solid #d1d5db; color: #9ca3af; background-color: #f3f4f6; cursor: not-allowed; border-radius: 3px; font-weight: bold;">&larr;</span>
            @else
            <a href="{{ $protocols->previousPageUrl() }}" style="padding: 5px 10px; border: 1px solid #a5a5a5; color: #111827; background-color: #ffffff; text-decoration: none; border-radius: 3px; font-weight: bold;">&larr;</a>
            @endif

            <span style="color: #4b5563; padding: 0 4px;">Страница {{ $protocols->currentPage() }}</span>

            @if ($protocols->hasMorePages())

            <a href="{{ $protocols->nextPageUrl() }}" style="padding: 5px 10px; border: 1px solid #a5a5a5; color: #111827; background-color: #ffffff; text-decoration: none; border-radius: 3px; font-weight: bold;">&rarr;</a>
            @else
            <span style="padding: 5px 10px; border: 1px solid #d1d5db; color: #9ca3af; background-color: #f3f4f6; cursor: not-allowed; border-radius: 3px; font-weight: bold;">&rarr;</span>
            @endif
        </div>

        <!-- Подключение нашего Pop-up окна редактирования -->
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

            // Переменная безопасно выводится в теге скрипта, исключая сбои экранирования
            allProtocols: @json($jsonProtocols),

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
</script>