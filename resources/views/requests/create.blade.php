<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('requests.index') }}" class="suzipo-back-btn" title="Назад к списку заявок">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('requests.index') }}" style="color: var(--text-secondary); text-decoration: none;">Заявки на обучение</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Создание новой заявки</span>
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px;">
            <h1 class="suzipo-form-title" style="margin-bottom: 0;">Создание новой заявки</h1>

            <label style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 10px; background-color: var(--bg-surface); border: 1px solid var(--border-color); cursor: pointer;">
                <input type="checkbox" name="one_time" form="request-create-form" value="1" {{ old('one_time') ? 'checked' : '' }}
                    style="width: 16px; height: 16px; accent-color: #FFD100; cursor: pointer;">
                <span style="font-size: 13px; font-weight: 500; color: var(--text-primary);">Одноразовая заявка</span>
            </label>
        </div>
        <!-- Префикс номера заявки (только для urp, urp admin) -->
        @if(auth()->user()->hasAnyRole(['urp', 'urp admin']))
        <!--<div class="col-span-full">
    <label for="req_prefix" class="block text-sm font-medium text-gray-700 mb-1">Префикс номера заявки</label>
    <select name="req_prefix" id="req_prefix"
            class="w-48 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <option value="ФЛ" {{ old('req_prefix') === 'ФЛ' ? 'selected' : '' }}>ФЛ (Физические лица)</option>
        <option value="ЮЛ" {{ old('req_prefix') === 'ЮЛ' ? 'selected' : '' }}>ЮЛ (Юридические лица)</option>
    </select>
</div>-->
        @endif

        <!-- Скрытое поле для ooo, ookoit, metodist (всегда ЮНГ) -->
        @if(!auth()->user()->hasAnyRole(['urp', 'urp admin']))
        <!-- <input type="hidden" name="req_prefix" value="ЮНГ"> -->
        @endif
        <form id="request-create-form" action="{{ route('requests.store') }}" method="POST">
            @csrf
            <script>
                function updateRequestNumber(selectedPrefix) {
                    const nextPrefixNumbers = @json($nextPrefixNumbers);
                    const numberInput = document.getElementById('display_req_id');
                    if (numberInput && nextPrefixNumbers[selectedPrefix]) {
                        numberInput.value = nextPrefixNumbers[selectedPrefix] + '-' + selectedPrefix;
                    }
                }
            </script>

            <div class="suzipo-grid-6">
                <!-- Ряд 1: Префикс (1), Формируемый номер (2), Дата начала (1), Дата окончания (1), Дата создания (1) -->
                <div class="suzipo-col-1">
                    <label class="suzipo-label">Тип (Префикс)</label>
                    @if($isUrp)
                    <div class="suzipo-field" style="min-height: 40px; display: flex; align-items: center; font-weight: 600; opacity: 0.85;">
                        ЮНГ
                    </div>
                    <input type="hidden" name="req_prefix" value="ЮНГ">
                    @else
                    <select name="req_prefix" onchange="updateRequestNumber(this.value)" class="suzipo-field">
                        <option value="ЮЛ" {{ old('req_prefix') == 'ЮЛ' ? 'selected' : '' }}>ЮЛ</option>
                        <option value="ФЛ" {{ old('req_prefix') == 'ФЛ' ? 'selected' : '' }}>ФЛ</option>
                    </select>
                    @endif
                </div>

                <div class="suzipo-col-2">
                    <label for="display_req_id" class="suzipo-label">Формируемый номер заявки</label>
                    <input type="text" id="display_req_id" readonly
                        value="{{ $nextPrefixNumbers[$isUrp ? 'ЮНГ' : 'ЮЛ'] }}-{{ $isUrp ? 'ЮНГ' : 'ЮЛ' }}"
                        class="suzipo-field"
                        style="font-weight: 700; color: #D97706 !important; cursor: not-allowed; letter-spacing: 0.03em;">
                </div>

                <div class="suzipo-col-1">
                    <label for="start_date" class="suzipo-label">Дата начала</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="suzipo-field">
                    @error('start_date') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="end_date" class="suzipo-label">Дата окончания</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="suzipo-field">
                    @error('end_date') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="issue_date" class="suzipo-label">Дата создания</label>
                    <input type="date" name="issue_date" id="issue_date" value="{{ now()->format('Y-m-d') }}"
                        class="suzipo-field" style="opacity: 0.7; cursor: not-allowed;" readonly>
                </div>

                <!-- Ряд 2: Форма образования, ИТР/рабочие, С отрывом от производства -->
                <div class="suzipo-col-2">
                    <label for="education_form" class="suzipo-label">Форма образования</label>
                    <select name="education_form" id="education_form" class="suzipo-field">
                        <option value="">---</option>
                        <option value="дистанционное" {{ old('education_form') == 'дистанционное' ? 'selected' : '' }}>Дистанционное</option>
                        <option value="заочное" {{ old('education_form') == 'заочное' ? 'selected' : '' }}>Заочное</option>
                        <option value="очное" {{ old('education_form') == 'очное' ? 'selected' : '' }}>Очное</option>
                        <option value="очное-заочное (вечернее)" {{ old('education_form') == 'очное-заочное (вечернее)' ? 'selected' : '' }}>Очное-заочное (вечернее)</option>
                        <option value="прочее" {{ old('education_form') == 'прочее' ? 'selected' : '' }}>Прочее</option>
                        <option value="самообразование" {{ old('education_form') == 'самообразование' ? 'selected' : '' }}>Самообразование</option>
                        <option value="семейное образование" {{ old('education_form') == 'семейное образование' ? 'selected' : '' }}>Семейное образование</option>
                        <option value="экстернат" {{ old('education_form') == 'экстернат' ? 'selected' : '' }}>Экстернат</option>
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="employee_type" class="suzipo-label">ИТР/рабочие</label>
                    <select name="employee_type" id="employee_type" class="suzipo-field">
                        <option value="">---</option>
                        <option value="ИТР(РСС)" {{ old('employee_type') == 'ИТР(РСС)' ? 'selected' : '' }}>ИТР(РСС)</option>
                        <option value="Рабочие" {{ old('employee_type') == 'Рабочие' ? 'selected' : '' }}>Рабочие</option>
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="production_break" class="suzipo-label">С отрывом от производства</label>
                    <select name="production_break" id="production_break" class="suzipo-field">
                        <option value="">---</option>
                        <option value="с отрывом от производства" {{ old('production_break') == 'с отрывом от производства' ? 'selected' : '' }}>С отрывом от производства</option>
                        <option value="без отрыва от производства" {{ old('production_break') == 'без отрыва от производства' ? 'selected' : '' }}>Без отрыва от производства</option>
                        <option value="без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий" {{ old('production_break') == 'без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий' ? 'selected' : '' }}>Без отрыва от производства с применением ЭО и ДОТ</option>
                    </select>
                </div>
                <!-- Учебное заведение -->
                <div x-data="providerSelector()" class="suzipo-col-3" style="z-index: 30;">
                    <label class="suzipo-label">Учебное заведение</label>
                    <input type="hidden" name="provider_id" x-model="selectedId">
                    <input type="hidden" name="new_provider_name" x-model="newProviderName">

                    <div style="position: relative;">
                        <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                            <span x-text="selectedName || '--- Выберите или введите провайдера ---'"
                                :style="!selectedName ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div x-show="open" @click.away="open = false" x-cloak
                            style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 380px; z-index: 50; overflow: hidden;">
                            <div style="display: flex; flex-direction: column; max-height: 380px;">
                                <div style="padding: 8px; border-bottom: 1px solid var(--border-color); background-color: var(--bg-surface);">
                                    <input type="text" x-model="search" placeholder="Поиск по провайдерам или введите новый..."
                                        class="suzipo-field" style="background-color: var(--bg-page) !important; padding: 7px 10px !important;"
                                        @click.stop @keydown.enter.prevent="addNewProvider()">
                                </div>
                                <div style="overflow-y: auto; flex: 1;">
                                    <div x-show="search && !isExistingProvider" @click="addNewProvider()"
                                        style="padding: 10px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); background-color: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; gap: 6px;">
                                        <span style="font-weight: 700;">+</span>
                                        <span>Добавить нового провайдера: "<strong x-text="search"></strong>"</span>
                                    </div>
                                    <template x-for="provider in filteredProviders" :key="provider.id">
                                        <div @click="selectProvider(provider)"
                                            style="padding: 9px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); line-height: 1.4; color: var(--text-primary);"
                                            :style="selectedId == provider.id ? 'background-color: rgba(255, 209, 0, 0.2); font-weight: 600;' : ''">
                                            <span x-text="provider.name"></span>
                                        </div>
                                    </template>
                                    <div x-show="filteredProviders.length === 0 && !search" style="padding: 16px; font-size: 13px; color: var(--text-secondary); text-align: center;">
                                        Начните вводить название провайдера
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function providerSelector() {
                        return {
                            open: false,
                            search: '',
                            selectedId: '{{ old('provider_id') }}',
                            selectedName: '',
                            newProviderName: '',
                            providers: [
                                @foreach($providers as $provider) {
                                    id: '{{ $provider->id }}',
                                    name: '{{ addslashes($provider->name) }}'
                                },
                                @endforeach
                            ],
                            get filteredProviders() {
                                if (!this.search) return this.providers;
                                return this.providers.filter(p =>
                                    p.name.toLowerCase().includes(this.search.toLowerCase())
                                );
                            },
                            get isExistingProvider() {
                                if (!this.search) return false;
                                return this.providers.some(p => p.name.toLowerCase() === this.search.toLowerCase());
                            },
                            selectProvider(provider) {
                                console.log('Выбран провайдер:', provider);
                                this.selectedId = provider.id;
                                this.selectedName = provider.name;
                                this.newProviderName = '';
                                this.open = false;
                                this.search = '';

                                // Отправляем событие для загрузки курсов
                                document.dispatchEvent(new CustomEvent('provider-selected', {
                                    detail: {
                                        providerId: provider.id
                                    }
                                }));
                            },
                            addNewProvider() {
                                if (!this.search) return;
                                const existing = this.providers.find(p => p.name.toLowerCase() === this.search.toLowerCase());
                                if (existing) {
                                    this.selectProvider(existing);
                                    return;
                                }

                                this.newProviderName = this.search;
                                this.selectedName = this.search;
                                this.selectedId = '';
                                this.open = false;
                                this.search = '';

                                // Для нового провайдера показываем курсы из courses_urp
                                document.dispatchEvent(new CustomEvent('provider-selected', {
                                    detail: {
                                        providerId: null
                                    }
                                }));
                            },
                            init() {
                                // НЕ загружаем курсы автоматически при инициализации
                                // Только если уже есть выбранный провайдер (например при редактировании)
                                if (this.selectedId && this.selectedId !== '') {
                                    const selected = this.providers.find(p => p.id == this.selectedId);
                                    if (selected) {
                                        this.selectedName = selected.name;
                                        // Загружаем курсы только если провайдер выбран
                                        document.dispatchEvent(new CustomEvent('provider-selected', {
                                            detail: {
                                                providerId: selected.id
                                            }
                                        }));
                                    }
                                }
                            }
                        }
                    }
                </script>

                <!-- Наименование ПО -->
                <div x-data="courseSelector()" class="suzipo-col-3" style="z-index: 25;">
                    <label class="suzipo-label">Наименование ПО</label>
                    <input type="hidden" name="course_id" x-model="selectedId">
                    <input type="hidden" name="new_course_name" x-model="newCourseName">
                    <input type="hidden" name="matrix_num" x-model="selectedMatrixNum">

                    <div style="position: relative;">
                        <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                            <span x-text="selectedName || message"
                                :style="(!selectedName && !providerSelected) ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div x-show="open" @click.away="open = false" x-cloak
                            style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 380px; z-index: 50; overflow: hidden;">
                            <template x-if="!providerSelected && !loading">
                                <div style="padding: 24px; text-align: center; font-weight: 600; color: var(--text-secondary);">
                                    Выберите учебное заведение
                                </div>
                            </template>

                            <template x-if="loading">
                                <div style="padding: 24px; text-align: center; color: var(--text-secondary);">
                                    Загрузка курсов...
                                </div>
                            </template>

                            <template x-if="providerSelected && !loading && courses.length === 0">
                                <div style="padding: 24px; text-align: center; color: var(--text-secondary);">
                                    Курсы не найдены
                                </div>
                            </template>

                            <template x-if="providerSelected && !loading && courses.length > 0">
                                <div style="display: flex; flex-direction: column; max-height: 380px;">
                                    <div style="padding: 8px; border-bottom: 1px solid var(--border-color); background-color: var(--bg-surface);">
                                        <input type="text" x-model="search" placeholder="Поиск по курсам или введите новый..."
                                            class="suzipo-field" style="background-color: var(--bg-page) !important; padding: 7px 10px !important;"
                                            @click.stop @keydown.enter.prevent="addNewCourse()">
                                    </div>
                                    <div style="overflow-y: auto; max-height: 300px;">
                                        <div x-show="search && !isExistingCourse" @click="addNewCourse()"
                                            style="padding: 10px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); background-color: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; gap: 6px;">
                                            <span style="font-weight: 700;">+</span>
                                            <span>Добавить новый курс: "<strong x-text="search"></strong>"</span>
                                        </div>
                                        <template x-for="course in filteredCourses" :key="course">
                                            <div @click="selectCourse(course)"
                                                style="padding: 9px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); line-height: 1.4; color: var(--text-primary);"
                                                :style="selectedName === course ? 'background-color: rgba(255, 209, 0, 0.2); font-weight: 600;' : ''">
                                                <span x-text="course"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- МОДАЛЬНОЕ ОКНО С x-teleport --}}
                    <template x-teleport="body">
                        <div x-show="showMatrixModal" x-cloak
                            @click="showMatrixModal = false; open = false; $dispatch('close-provider-dropdown')"
                            style="position: fixed; inset: 0; z-index: 99999; background-color: rgba(0, 0, 0, 0.55); padding: 16px;">
                            <div style="display: flex; align-items: center; justify-content: center; min-height: 100%;">
                                <div @click.stop style="background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 16px; max-width: 440px; width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.25); color: var(--text-primary);">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                                        <h3 style="font-size: 16px; font-weight: 700; margin: 0;">Добавление нового курса</h3>
                                        <button type="button" @click="showMatrixModal = false" style="background: none; border: none; font-size: 22px; color: var(--text-secondary); cursor: pointer;">&times;</button>
                                    </div>
                                    <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">Выберите матрицу для добавления курса в справочник:</p>
                                    <div style="font-size: 13px; font-weight: 600; background-color: var(--bg-surface); border-radius: 10px; padding: 10px 14px; margin-bottom: 16px;">
                                        <span x-text="pendingCourseName"></span>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 10px; cursor: pointer; background-color: var(--bg-surface);">
                                            <input type="radio" name="matrix_select" value="matrix_courses" x-model="selectedMatrix">
                                            <span style="font-size: 13px; font-weight: 500;">Matrix Courses</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 10px; cursor: pointer; background-color: var(--bg-surface);">
                                            <input type="radio" name="matrix_select" value="matrix_dpo" x-model="selectedMatrix">
                                            <span style="font-size: 13px; font-weight: 500;">Matrix DPO</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 10px; cursor: pointer; background-color: var(--bg-surface);">
                                            <input type="radio" name="matrix_select" value="matrix_ot" x-model="selectedMatrix">
                                            <span style="font-size: 13px; font-weight: 500;">Matrix OT</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 10px; cursor: pointer; background-color: var(--bg-surface);">
                                            <input type="radio" name="matrix_select" value="matrix_po" x-model="selectedMatrix">
                                            <span style="font-size: 13px; font-weight: 500;">Matrix PO</span>
                                        </label>
                                    </div>
                                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                                        <button type="button" @click="showMatrixModal = false" class="btn-suzipo-cancel">Отмена</button>
                                        <button type="button" @click="saveSelectedMatrix(); open = false; $dispatch('close-provider-dropdown')"
                                            class="btn-suzipo-save" :disabled="!selectedMatrix">Сохранить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Информация о выбранном курсе -->
                    <div x-show="selectedName !== '' && selectedMatrixNum !== 5" x-cloak
                        style="margin-top: 10px; padding: 12px; background-color: var(--bg-surface); border-radius: 12px; border: 1px solid var(--border-color);">
                        <div style="font-size: 11px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">Информация о курсе</div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; font-size: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 5px 8px; background-color: var(--bg-page); border-radius: 6px;">
                                <span style="color: var(--text-secondary);">Объём нагрузки:</span>
                                <span style="font-weight: 600; color: var(--text-primary);" x-text="courseInfo.hours || '—'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 5px 8px; background-color: var(--bg-page); border-radius: 6px;">
                                <span style="color: var(--text-secondary);">Теория:</span>
                                <span style="font-weight: 600; color: var(--text-primary);" x-text="courseInfo.theory_hours || '—'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 5px 8px; background-color: var(--bg-page); border-radius: 6px;">
                                <span style="color: var(--text-secondary);">Самост. подг.:</span>
                                <span style="font-weight: 600; color: var(--text-primary);" x-text="courseInfo.self_study_hours || '—'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 5px 8px; background-color: var(--bg-page); border-radius: 6px;">
                                <span style="color: var(--text-secondary);">Практика:</span>
                                <span style="font-weight: 600; color: var(--text-primary);" x-text="courseInfo.practical_hours || '—'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 5px 8px; background-color: var(--bg-page); border-radius: 6px;">
                                <span style="color: var(--text-secondary);">Разряд:</span>
                                <span style="font-weight: 600; color: var(--text-primary);" x-text="courseInfo.rank || '—'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function courseSelector() {
                        return {
                            open: false,
                            search: '',
                            selectedId: null,
                            selectedName: '',
                            newCourseName: '',
                            courses: [],
                            loading: false,
                            providerSelected: false,
                            providerId: null,
                            message: '--- Выберите или введите курс ---',
                            courseInfo: {},
                            courseInfoMap: {},

                            // Флаги для модального окна
                            showMatrixModal: false,
                            pendingCourseName: '',
                            selectedMatrix: null, // Выбранная матрица
                            courseMatrixNums: {}, // 
                            selectedMatrixNum: null,
                            isRnYuganskProvider: false,

                            get filteredCourses() {
                                if (!this.search) return this.courses;
                                return this.courses.filter(c =>
                                    c.toLowerCase().includes(this.search.toLowerCase())
                                );
                            },

                            get isExistingCourse() {
                                if (!this.search) return false;
                                return this.courses.some(c => c.toLowerCase() === this.search.toLowerCase());
                            },

                            async loadCourses(providerId) {
                                console.log('Загрузка курсов для провайдера:', providerId);

                                this.providerId = providerId;
                                // Проверяем, является ли провайдер РН-Юганскнефтегаз (91, 92)
                                this.isRnYuganskProvider = [66, 67, 91, 92].includes(Number(providerId));
                                console.log('isRnYuganskProvider:', this.isRnYuganskProvider);

                                // Сбрасываем состояние
                                this.loading = true;
                                this.open = true;
                                this.search = '';
                                this.selectedName = '';
                                this.selectedId = null;
                                this.newCourseName = '';
                                this.courses = [];
                                this.courseMatrixNums = {}; // ← Добавляем объект для хранения matrix_num
                                this.courseInfoMap = {}; // для отображения информации по выбранному курсу

                                try {
                                    const response = await fetch(`/api/courses-by-provider?provider_id=${providerId}`, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json',
                                        }
                                    });

                                    if (!response.ok) {
                                        throw new Error('Ошибка загрузки');
                                    }

                                    const data = await response.json();
                                    console.log('Получено курсов:', data.courses.length);
                                    this.courses = data.courses.map(c => c.name);

                                    // Сохраняем matrix_num для каждого курса
                                    this.courseMatrixNums = {};
                                    this.courseInfoMap = {};
                                    data.courses.forEach(c => {
                                        this.courseMatrixNums[c.name] = c.matrix_num;
                                        this.courseInfoMap[c.name] = {
                                            hours: c.hours,
                                            theory_hours: c.theory_hours,
                                            self_study_hours: c.self_study_hours,
                                            practical_hours: c.practical_hours,
                                            rank: c.rank,
                                        };

                                    });

                                    //                this.courses = data.courses;
                                    this.providerSelected = true;
                                } catch (error) {
                                    console.error('Ошибка загрузки курсов:', error);
                                    this.courses = [];
                                    this.providerSelected = false;
                                } finally {
                                    this.loading = false;
                                }
                            },

                            selectCourse(course) {
                                console.log('Выбран курс:', course);
                                console.log('matrix_num:', this.courseMatrixNums[course]);
                                this.selectedName = course;
                                this.selectedId = null;
                                this.newCourseName = course; // ← Сохраняем название курса
                                this.selectedMatrixNum = this.courseMatrixNums[course] || null; // ← Устанавливаем matrix_num
                                this.courseInfo = this.courseInfoMap[course] || {};
                                this.open = false;
                                this.search = '';
                            },

                            addNewCourse() {
                                console.log('addNewCourse called');
                                console.log('search:', this.search);
                                console.log('isRnYuganskProvider:', this.isRnYuganskProvider);

                                if (!this.search) return;

                                const existing = this.courses.find(c => c.toLowerCase() === this.search.toLowerCase());
                                if (existing) {
                                    this.selectCourse(existing);
                                    return;
                                }

                                // Если это РН-Юганскнефтегаз — показываем модальное окно
                                if (this.isRnYuganskProvider) {
                                    console.log('Показываем модальное окно');
                                    this.pendingCourseName = this.search;
                                    this.newCourseName = this.search; // ← Сохраняем название
                                    this.selectedMatrix = null; // ← Сбрасываем выбранную матрицу
                                    this.showMatrixModal = true;
                                    this.open = false; // ← Закрываем выпадающий список
                                } else {
                                    // Иначе сразу сохраняем в courses_urp
                                    console.log('Сохраняем в courses_urp');
                                    this.newCourseName = this.search; // ← Сохраняем название
                                    this.saveCourseToUrp(this.search);
                                    this.open = false; // ← Закрываем выпадающий список
                                }
                            },

                            // ← сохраняем выбранную матрицу
                            async saveSelectedMatrix() {
                                if (!this.selectedMatrix) {
                                    alert('Пожалуйста, выберите матрицу');
                                    return;
                                }

                                await this.saveCourseToMatrix(this.selectedMatrix);
                            },
                            async saveCourseToUrp(name) {
                                try {
                                    console.log('saveCourseToUrp called:', name);

                                    const response = await fetch('/api/courses', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                            'Accept': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            name: name,
                                            provider_id: this.providerId,
                                        })
                                    });

                                    const data = await response.json();
                                    console.log('Response:', data);

                                    if (data.success) {
                                        // Добавляем курс в список
                                        this.courses.push(name);
                                        this.courses.sort();

                                        // Выбираем его и сохраняем название
                                        this.selectedName = name;
                                        this.newCourseName = name; // ← Сохраняем название
                                        this.selectedMatrixNum = data.matrix_num; // ← Сохраняем matrix_num
                                        this.courseInfo = {
                                            hours: data.course_info?.hours ?? null,
                                            theory_hours: data.course_info?.theory_hours ?? null,
                                            self_study_hours: data.course_info?.self_study_hours ?? null,
                                            practical_hours: data.course_info?.practical_hours ?? null,
                                            rank: data.course_info?.rank ?? null,
                                        };
                                        this.selectedId = null;
                                        this.open = false;

                                        alert(data.message);
                                    } else {
                                        alert(data.error || 'Ошибка при сохранении курса');
                                    }
                                } catch (error) {
                                    console.error('Ошибка:', error);
                                    alert('Ошибка при сохранении курса');
                                }
                            },

                            async saveCourseToMatrix(matrix) {
                                try {


                                    console.log('saveCourseToMatrix called:', matrix);
                                    console.log('pendingCourseName:', this.pendingCourseName);
                                    console.log('providerId:', this.providerId);

                                    const response = await fetch('/api/courses', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                            'Accept': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            name: this.pendingCourseName,
                                            provider_id: this.providerId,
                                            matrix: matrix,
                                        })
                                    });

                                    const data = await response.json();
                                    console.log('Response:', data);

                                    if (data.success) {
                                        console.log('matrix_num from server:', data.matrix_num);
                                        // Добавляем курс в список
                                        this.courses.push(this.pendingCourseName);
                                        this.courses.sort();


                                        // Выбираем его и сохраняем название
                                        this.selectedName = this.pendingCourseName;
                                        this.newCourseName = this.pendingCourseName; // ← Сохраняем название
                                        this.selectedMatrixNum = data.matrix_num; // ← Сохраняем matrix_num
                                        this.selectedMatrix = matrix;
                                        console.log('selectedMatrixNum:', this.selectedMatrixNum);
                                        this.courseInfo = data.course_info || {};
                                        this.selectedId = null;

                                        // Закрываем модальное окно
                                        this.showMatrixModal = false;
                                        this.pendingCourseName = '';
                                        this.open = false; // ← Закрываем выпадающий список

                                        alert(data.message);
                                    } else {
                                        alert(data.error || 'Ошибка при сохранении курса');
                                    }
                                } catch (error) {
                                    console.error('Ошибка:', error);
                                    alert('Ошибка при сохранении курса');
                                }
                            },
                            reset() {
                                this.providerSelected = false;
                                this.courses = [];
                                this.selectedName = '';
                                this.selectedId = null;
                                this.newCourseName = '';
                                this.search = '';
                                this.loading = false;
                                this.providerId = null;
                                this.isRnYuganskProvider = false;
                                this.showMatrixModal = false;
                            },

                            init() {
                                // Слушаем событие от провайдера
                                document.addEventListener('provider-selected', (event) => {
                                    console.log('provider-selected event:', event.detail);
                                    if (event.detail.providerId) {
                                        this.loadCourses(event.detail.providerId);
                                    } else {
                                        this.reset();
                                    }
                                });

                                // Слушаем событие выбора матрицы
                                document.addEventListener('save-course', (event) => {
                                    console.log('save-course event:', event.detail);
                                    this.saveCourseToMatrix(event.detail.matrix);
                                });
                            }
                        }
                    }
                </script>

                <!-- Место проведения (страна) -->
                <div class="suzipo-col-2">
                    <label for="country" class="suzipo-label">Место проведения (страна)</label>
                    <input type="text" name="country" id="country" value="{{ old('country', 'Россия') }}" class="suzipo-field">
                </div>

                <!-- Место проведения (город) -->
                <div x-data="citySelector()" class="suzipo-col-2" style="z-index: 20;">
                    <label class="suzipo-label">Место проведения (город)</label>
                    <input type="hidden" name="city_id" x-model="selectedId">
                    <input type="hidden" name="new_city_name" x-model="newCityName">

                    <div style="position: relative;">
                        <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                            <span x-text="selectedName || '--- Выберите или введите город ---'"
                                  :style="!selectedName ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="open" @click.away="open = false" x-cloak
                             style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 360px; z-index: 50; overflow: hidden;">
                            <div style="display: flex; flex-direction: column; max-height: 360px;">
                                <div style="padding: 8px; border-bottom: 1px solid var(--border-color); background-color: var(--bg-surface);">
                                    <input type="text" x-model="search" placeholder="Поиск по городам или введите новый..."
                                           class="suzipo-field" style="background-color: var(--bg-page) !important; padding: 7px 10px !important;"
                                           @click.stop @keydown.enter.prevent="addNewCity()">
                                </div>
                                <div style="overflow-y: auto; flex: 1;">
                                    <div x-show="search && !isExistingCity" @click="addNewCity()"
                                         style="padding: 10px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); background-color: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; gap: 6px;">
                                        <span style="font-weight: 700;">+</span>
                                        <span>Добавить новый город: "<strong x-text="search"></strong>"</span>
                                    </div>
                                    <template x-for="city in filteredCities" :key="city.id">
                                        <div @click="selectCity(city)"
                                             style="padding: 9px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); color: var(--text-primary);"
                                             :style="selectedId == city.id ? 'background-color: rgba(255, 209, 0, 0.2); font-weight: 600;' : ''">
                                            <span x-text="city.name"></span>
                                        </div>
                                    </template>
                                    <div x-show="filteredCities.length === 0 && !search" style="padding: 16px; font-size: 13px; color: var(--text-secondary); text-align: center;">
                                        Начните вводить название города
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function citySelector() {
                        return {
                            open: false,
                            search: '',
                            selectedId: '{{ old(' city_id ', $defaultCityId ?? '') }}',
                            selectedName: '',
                            newCityName: '',
                            cities: [
                                @foreach($cities as $city) {
                                    id: '{{ $city->id }}',
                                    name: '{{ addslashes($city->city) }}'
                                },
                                @endforeach
                            ],
                            get filteredCities() {
                                if (!this.search) return this.cities;
                                return this.cities.filter(c =>
                                    c.name.toLowerCase().includes(this.search.toLowerCase())
                                );
                            },
                            get isExistingCity() {
                                if (!this.search) return false;
                                return this.cities.some(c => c.name.toLowerCase() === this.search.toLowerCase());
                            },
                            selectCity(city) {
                                this.selectedId = city.id;
                                this.selectedName = city.name;
                                this.newCityName = '';
                                this.open = false;
                                this.search = '';
                            },
                            addNewCity() {
                                if (!this.search) return;
                                const existing = this.cities.find(c => c.name.toLowerCase() === this.search.toLowerCase());
                                if (existing) {
                                    this.selectCity(existing);
                                    return;
                                }
                                this.newCityName = this.search;
                                this.selectedName = this.search;
                                this.selectedId = '';
                                this.open = false;
                            },
                            init() {
                                if (this.selectedId) {
                                    const selected = this.cities.find(c => c.id == this.selectedId);
                                    if (selected) {
                                        this.selectedName = selected.name;
                                    }
                                }
                                // Если город Нефтеюганск не выбран, но есть ID по умолчанию
                                if (!this.selectedId && this.cities.length > 0) {
                                    const defaultCity = this.cities.find(c => c.name.toLowerCase().includes('нефтеюганск'));
                                    if (defaultCity) {
                                        this.selectedId = defaultCity.id;
                                        this.selectedName = defaultCity.name;
                                    }
                                }
                            }
                        }
                    }
                </script>

                <!-- Профессия, присваиваемая по результатам обучения -->
                <div x-data="professionSelector()" class="suzipo-col-2" style="z-index: 15;">
                    <label class="suzipo-label">Присваиваемая профессия</label>
                    <input type="hidden" name="profession_id" x-model="selectedId">
                    <input type="hidden" name="new_profession_name" x-model="newProfessionName">

                    <div style="position: relative;">
                        <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                            <span x-text="selectedName || '--- Выберите или введите профессию ---'"
                                  :style="!selectedName ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="open" @click.away="open = false" x-cloak
                             style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 360px; z-index: 50; overflow: hidden;">
                            <div style="display: flex; flex-direction: column; max-height: 360px;">
                                <div style="padding: 8px; border-bottom: 1px solid var(--border-color); background-color: var(--bg-surface);">
                                    <input type="text" x-model="search" placeholder="Поиск по профессиям или введите новую..."
                                           class="suzipo-field" style="background-color: var(--bg-page) !important; padding: 7px 10px !important;"
                                           @click.stop @keydown.enter.prevent="addNewProfession()">
                                </div>
                                <div style="overflow-y: auto; flex: 1;">
                                    <div x-show="search && !isExistingProfession" @click="addNewProfession()"
                                         style="padding: 10px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); background-color: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; gap: 6px;">
                                        <span style="font-weight: 700;">+</span>
                                        <span>Добавить новую профессию: "<strong x-text="search"></strong>"</span>
                                    </div>
                                    <template x-for="profession in filteredProfessions" :key="profession.id">
                                        <div @click="selectProfession(profession)"
                                             style="padding: 9px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--border-color); color: var(--text-primary);"
                                             :style="selectedId == profession.id ? 'background-color: rgba(255, 209, 0, 0.2); font-weight: 600;' : ''">
                                            <span x-text="profession.name"></span>
                                        </div>
                                    </template>
                                    <div x-show="filteredProfessions.length === 0 && !search" style="padding: 16px; font-size: 13px; color: var(--text-secondary); text-align: center;">
                                        Начните вводить название профессии
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function professionSelector() {
                        return {
                            open: false,
                            search: '',
                            selectedId: '{{ old(' profession_id ') }}',
                            selectedName: '',
                            newProfessionName: '',
                            professions: [
                                @foreach($professions as $profession) {
                                    id: '{{ $profession->id }}',
                                    name: '{{ addslashes($profession->name) }}'
                                },
                                @endforeach
                            ],
                            get filteredProfessions() {
                                if (!this.search) return this.professions;
                                return this.professions.filter(p =>
                                    p.name.toLowerCase().includes(this.search.toLowerCase())
                                );
                            },
                            get isExistingProfession() {
                                if (!this.search) return false;
                                return this.professions.some(p => p.name.toLowerCase() === this.search.toLowerCase());
                            },
                            selectProfession(profession) {
                                this.selectedId = profession.id;
                                this.selectedName = profession.name;
                                this.newProfessionName = '';
                                this.open = false;
                                this.search = '';
                            },
                            addNewProfession() {
                                if (!this.search) return;
                                const existing = this.professions.find(p => p.name.toLowerCase() === this.search.toLowerCase());
                                if (existing) {
                                    this.selectProfession(existing);
                                    return;
                                }
                                this.newProfessionName = this.search;
                                this.selectedName = this.search;
                                this.selectedId = '';
                                this.open = false;
                            },
                            init() {
                                if (this.selectedId) {
                                    const selected = this.professions.find(p => p.id == this.selectedId);
                                    if (selected) {
                                        this.selectedName = selected.name;
                                    }
                                }
                            }
                        }
                    }
                </script>
                <!-- Причина обучения -->
                <div class="suzipo-col-2">
                    <label for="learn_reason_id" class="suzipo-label">Причина обучения</label>
                    <select name="learn_reason_id" id="learn_reason_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($learnReasons as $reason)
                            <option value="{{ $reason->id }}" {{ old('learn_reason_id') == $reason->id ? 'selected' : '' }}>{{ $reason->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ресурс обучения/оценки -->
                <div class="suzipo-col-2">
                    <label for="learning_resource_id" class="suzipo-label">Ресурс обучения/оценки</label>
                    <select name="learning_resource_id" id="learning_resource_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($learningResources as $resource)
                            <option value="{{ $resource->id }}" {{ old('learning_resource_id') == $resource->id ? 'selected' : '' }}>{{ $resource->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Вид обучения/оценки -->
                <div class="suzipo-col-2">
                    <label for="learning_type_id" class="suzipo-label">Вид обучения/оценки</label>
                    <select name="learning_type_id" id="learning_type_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($learningTypes as $type)
                            <option value="{{ $type->id }}" {{ old('learning_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Вид мероприятия -->
                <div class="suzipo-col-2">
                    <label for="event_type_id" class="suzipo-label">Вид мероприятия</label>
                    <select name="event_type_id" id="event_type_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($eventsTypes as $eventType)
                            <option value="{{ $eventType->id }}" {{ old('event_type_id') == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Дисциплина -->
                <div class="suzipo-col-2">
                    <label for="discipline_id" class="suzipo-label">Дисциплина</label>
                    <select name="discipline_id" id="discipline_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($disciplines as $discipline)
                            <option value="{{ $discipline->id }}" {{ old('discipline_id') == $discipline->id ? 'selected' : '' }}>{{ $discipline->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Себестоимость/Прибыль -->
                <div class="suzipo-col-2">
                    <label for="cost_profit" class="suzipo-label">Себестоимость/Прибыль</label>
                    <select name="cost_profit" id="cost_profit" class="suzipo-field">
                        <option value="">---</option>
                        <option value="Себестоимость" {{ old('cost_profit') == 'Себестоимость' ? 'selected' : '' }}>Себестоимость</option>
                        <option value="Прибыль" {{ old('cost_profit') == 'Прибыль' ? 'selected' : '' }}>Прибыль</option>
                    </select>
                </div>
            </div>

            <!-- Нижние кнопки действий -->
            <div class="suzipo-actions">
                <a href="{{ route('requests.index') }}" class="btn-suzipo-cancel">Отмена</a>

                <button type="submit" name="action" value="save_and_employees"
                        style="padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; background-color: rgba(16, 185, 129, 0.14); color: #10B981; border: 1px solid #10B981; cursor: pointer;">
                    Сохранить и добавить сотрудников
                </button>

                <button type="submit" name="action" value="save" class="btn-suzipo-save">
                    Сохранить заявку
                </button>
            </div>

        </form>
    </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Находим все input[type="date"] с классом date-input
            document.querySelectorAll('.date-input').forEach(function(input) {
                // Находим соответствующий span (следующий элемент после input)
                const span = input.nextElementSibling;

                if (!span) return;

                // При фокусе - скрываем placeholder
                input.addEventListener('focus', function() {
                    span.classList.add('hidden');
                });

                // При потере фокуса - если поле пустое, показываем placeholder
                input.addEventListener('blur', function() {
                    if (!input.value) {
                        span.classList.remove('hidden');
                    }
                });

                // При выборе даты - скрываем placeholder навсегда
                input.addEventListener('change', function() {
                    if (input.value) {
                        span.classList.add('hidden');
                    }
                });

                // При загрузке страницы, если значение уже есть (для edit)
                if (input.value) {
                    span.classList.add('hidden');
                }
            });
        });
    </script>
</x-layouts.app-with-sidebar>