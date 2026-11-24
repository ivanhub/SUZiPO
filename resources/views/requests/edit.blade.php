<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('requests.index') }}" class="suzipo-back-btn" title="Назад к списку заявок">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('requests.index') }}" style="color: var(--text-secondary); text-decoration: none;">Заявки на обучение</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Редактирование заявки #{{ $request->id }}</span>
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px;">
            <h1 class="suzipo-form-title" style="margin-bottom: 0;">Редактирование заявки #{{ $request->id }}</h1>
            <label style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 10px; background-color: var(--bg-surface); border: 1px solid var(--border-color); cursor: pointer;">
                <input type="checkbox" name="one_time" form="request-edit-form" value="1" {{ old('one_time', $request->one_time) ? 'checked' : '' }}
                       style="width: 16px; height: 16px; accent-color: #FFD100; cursor: pointer;">
                <span style="font-size: 13px; font-weight: 500; color: var(--text-primary);">Одноразовая заявка</span>
            </label>
        </div>

        <form id="request-edit-form" action="{{ route('requests.update', $request) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="suzipo-grid-6">
                <!-- Ряд 1: Номер заявки и 3 даты -->
                <div class="suzipo-col-2">
                    <label class="suzipo-label">Номер заявки</label>
                    <input type="text" value="{{ $request->req_id ?? '—' }}" class="suzipo-field" style="opacity: 0.7; cursor: not-allowed;" readonly>
                </div>

                <div class="suzipo-col-1">
                    <label for="start_date" class="suzipo-label">Дата начала</label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ $request->start_date ? $request->start_date->format('Y-m-d') : old('start_date', '') }}"
                           class="suzipo-field">
                    @error('start_date') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="end_date" class="suzipo-label">Дата окончания</label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ $request->end_date ? $request->end_date->format('Y-m-d') : old('end_date', '') }}"
                           class="suzipo-field">
                    @error('end_date') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="issue_date" class="suzipo-label">Дата оформления</label>
                    <input type="date" name="issue_date" id="issue_date"
                           value="{{ $request->issue_date ? $request->issue_date->format('Y-m-d') : old('issue_date', '') }}"
                           class="suzipo-field">
                    @error('issue_date') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <!-- Ряд 2: Форма образования, ИТР/рабочие, С отрывом от производства -->
                <div class="suzipo-col-2">
                    <label for="education_form" class="suzipo-label">Форма образования</label>
                    <select name="education_form" id="education_form" class="suzipo-field">
                        <option value="">---</option>
                        <option value="дистанционное" {{ old('education_form', $request->education_form) == 'дистанционное' ? 'selected' : '' }}>Дистанционное</option>
                        <option value="заочное" {{ old('education_form', $request->education_form) == 'заочное' ? 'selected' : '' }}>Заочное</option>
                        <option value="очное" {{ old('education_form', $request->education_form) == 'очное' ? 'selected' : '' }}>Очное</option>
                        <option value="очное-заочное (вечернее)" {{ old('education_form', $request->education_form) == 'очное-заочное (вечернее)' ? 'selected' : '' }}>Очное-заочное (вечернее)</option>
                        <option value="прочее" {{ old('education_form', $request->education_form) == 'прочее' ? 'selected' : '' }}>Прочее</option>
                        <option value="самообразование" {{ old('education_form', $request->education_form) == 'самообразование' ? 'selected' : '' }}>Самообразование</option>
                        <option value="семейное образование" {{ old('education_form', $request->education_form) == 'семейное образование' ? 'selected' : '' }}>Семейное образование</option>
                        <option value="экстернат" {{ old('education_form', $request->education_form) == 'экстернат' ? 'selected' : '' }}>Экстернат</option>
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="employee_type" class="suzipo-label">ИТР/рабочие</label>
                    <select name="employee_type" id="employee_type" class="suzipo-field">
                        <option value="">---</option>
                        <option value="ИТР(РСС)" {{ old('employee_type', $request->employee_type) == 'ИТР(РСС)' ? 'selected' : '' }}>ИТР(РСС)</option>
                        <option value="Рабочие" {{ old('employee_type', $request->employee_type) == 'Рабочие' ? 'selected' : '' }}>Рабочие</option>
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="production_break" class="suzipo-label">С отрывом от производства</label>
                    <select name="production_break" id="production_break" class="suzipo-field">
                        <option value="">---</option>
                        <option value="с отрывом от производства" {{ old('production_break', $request->production_break) == 'с отрывом от производства' ? 'selected' : '' }}>С отрывом от производства</option>
                        <option value="без отрыва от производства" {{ old('production_break', $request->production_break) == 'без отрыва от производства' ? 'selected' : '' }}>Без отрыва от производства</option>
                        <option value="без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий" {{ old('production_break', $request->production_break) == 'без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий' ? 'selected' : '' }}>Без отрыва от производства с применением ЭО и ДОТ</option>
                    </select>
                </div>
                <!-- Ряд 3: Учебное заведение (провайдер) и Наименование курса -->
                <div x-data="editProviderSelector()" class="suzipo-col-3" style="z-index: 25;">
                    <label class="suzipo-label">Учебное заведение (провайдер)</label>
                    <input type="hidden" name="provider_id" x-model="selectedId">
                    <input type="hidden" name="new_provider_name" x-model="newProviderName">
                    <div style="position: relative;">
                    <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                        <span x-text="selectedName || '--- Выберите или введите провайдера ---'"
                              :style="!selectedName ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
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
                <!-- Наименование курса (тематика) -->
                <div x-data="editCourseSelector()" class="suzipo-col-3" style="z-index: 25;">
                    <label class="suzipo-label">Наименование курса (тематика)</label>
                    <input type="hidden" name="course_id" x-model="selectedId">
                    <input type="hidden" name="new_course_name" x-model="newCourseName">
                    <input type="hidden" name="matrix_num" x-model="selectedMatrixNum">
                    <div style="position: relative;">
                    <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                        <span x-text="selectedName || message"
                              :style="(!selectedName && !providerSelected) ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <div x-show="open" @click.away="open = false" x-cloak
                         style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 380px; display: flex; flex-direction: column; z-index: 50; overflow: hidden;">

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
                            <div style="display: flex; flex-direction: column; overflow: hidden;">
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
                    <!-- Модальное окно выбора матрицы -->
                    <template x-teleport="body">
                        <div x-show="showMatrixModal" x-cloak
                             @click="showMatrixModal = false; open = false; $dispatch('close-provider-dropdown')"
                             style="position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.55); padding: 16px;">
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
                <!-- Ряд 4: Страна, Город, Профессия -->
                <div class="suzipo-col-2">
                    <label for="country" class="suzipo-label">Место проведения (страна)</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $request->country) }}" class="suzipo-field">
                </div>

                <!-- Место проведения (город) -->
                <div x-data="editCitySelector()" class="suzipo-col-2" style="position: relative; z-index: 20;">
                    <label class="suzipo-label">Место проведения (город)</label>
                    <input type="hidden" name="city_id" x-model="selectedId">
                    <input type="hidden" name="new_city_name" x-model="newCityName">

                    <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                        <span x-text="selectedName || '--- Выберите или введите город ---'"
                              :style="!selectedName ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <div x-show="open" @click.away="open = false" x-cloak
                         style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 360px; display: flex; flex-direction: column; z-index: 50; overflow: hidden;">
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
                        </div>
                    </div>
                </div>

                <!-- Профессия -->
                <div x-data="editProfessionSelector()" class="suzipo-col-2" style="position: relative; z-index: 15;">
                    <label class="suzipo-label">Присваиваемая профессия</label>
                    <input type="hidden" name="profession_id" x-model="selectedId">
                    <input type="hidden" name="new_profession_name" x-model="newProfessionName">

                    <div @click="open = !open; if(!open) search = ''" class="suzipo-field" style="cursor: pointer; min-height: 40px; display: flex; align-items: center; justify-content: space-between;">
                        <span x-text="selectedName || '--- Выберите или введите профессию ---'"
                              :style="!selectedName ? 'color: var(--text-secondary);' : 'color: var(--text-primary);'"></span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--text-secondary); flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <div x-show="open" @click.away="open = false" x-cloak
                         style="position: absolute; left: 0; right: 0; top: calc(100% + 4px); background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.18); max-height: 360px; display: flex; flex-direction: column; z-index: 50; overflow: hidden;">
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
                        </div>
                    </div>
                </div>
                <!-- Ряд 5: Причина, Ресурс, Вид обучения -->
                <div class="suzipo-col-2">
                    <label for="learn_reason_id" class="suzipo-label">Причина обучения</label>
                    <select name="learn_reason_id" id="learn_reason_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($learnReasons as $reason)
                            <option value="{{ $reason->id }}" {{ old('learn_reason_id', $request->learn_reason_id) == $reason->id ? 'selected' : '' }}>{{ $reason->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="learning_resource_id" class="suzipo-label">Ресурс обучения/оценки</label>
                    <select name="learning_resource_id" id="learning_resource_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($learningResources as $resource)
                            <option value="{{ $resource->id }}" {{ old('learning_resource_id', $request->learning_resource_id) == $resource->id ? 'selected' : '' }}>{{ $resource->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="learning_type_id" class="suzipo-label">Вид обучения/оценки</label>
                    <select name="learning_type_id" id="learning_type_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($learningTypes as $type)
                            <option value="{{ $type->id }}" {{ old('learning_type_id', $request->learning_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ряд 6: Вид мероприятия, Дисциплина, Себестоимость/Прибыль -->
                <div class="suzipo-col-2">
                    <label for="event_type_id" class="suzipo-label">Вид мероприятия</label>
                    <select name="event_type_id" id="event_type_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($eventsTypes as $eventType)
                            <option value="{{ $eventType->id }}" {{ old('event_type_id', $request->event_type_id) == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="discipline_id" class="suzipo-label">Дисциплина</label>
                    <select name="discipline_id" id="discipline_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($disciplines as $discipline)
                            <option value="{{ $discipline->id }}" {{ old('discipline_id', $request->discipline_id) == $discipline->id ? 'selected' : '' }}>{{ $discipline->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="suzipo-col-2">
                    <label for="cost_profit" class="suzipo-label">Себестоимость/Прибыль</label>
                    <select name="cost_profit" id="cost_profit" class="suzipo-field">
                        <option value="">---</option>
                        <option value="Себестоимость" {{ old('cost_profit', $request->cost_profit) == 'Себестоимость' ? 'selected' : '' }}>Себестоимость</option>
                        <option value="Прибыль" {{ old('cost_profit', $request->cost_profit) == 'Прибыль' ? 'selected' : '' }}>Прибыль</option>
                    </select>
                </div>
            </div>
            <div class="suzipo-section-title">Назначенные ресурсы</div>
            <div class="suzipo-grid-6">
                <!-- Аудитория -->
                <div class="suzipo-col-2">
                    <label for="audience_id" class="suzipo-label">Аудитория</label>
                    <select name="audience_id" id="audience_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($audiences as $audience)
                            @php
                                $isBusy = false;
                                if ($request->start_date && $request->end_date) {
                                    $isBusy = \App\Models\Booking::where('audience_id', $audience->id)
                                        ->where(function ($query) use ($request) {
                                            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                                                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                                                  ->orWhere(function ($query) use ($request) {
                                                      $query->where('start_date', '<=', $request->start_date)
                                                            ->where('end_date', '>=', $request->end_date);
                                                  });
                                        })
                                        ->where('id', '!=', $request->booking_id ?? null)
                                        ->exists();
                                }
                                $employeesCount = \App\Models\RequestEmployee::where('request_id', $request->id)->count();
                                $availableSeats = null;
                                if ($audience->seats && $audience->number !== 'ДОТ' && $audience->location !== 'Свободное местоположение') {
                                    $availableSeats = (int)$audience->seats - $employeesCount;
                                }
                            @endphp
                            <option value="{{ $audience->id }}"
                                    data-seats="{{ $audience->seats }}"
                                    {{ old('audience_id', $request->audience_id) == $audience->id ? 'selected' : '' }}
                                    {{ $isBusy ? 'disabled style="color:#EF4444;"' : '' }}>
                                {{ $audience->number }} ({{ $audience->location }})
                                @if($availableSeats !== null)
                                    — свободно: {{ $availableSeats }}@if($audience->seats) / {{ $audience->seats }}@endif мест
                                @endif
                                @if($isBusy) — ЗАНЯТА @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ФИО преподавателя -->
                <div class="suzipo-col-2">
                    <label for="teacher_id" class="suzipo-label">ФИО преподавателя</label>
                    <select name="teacher_id" id="teacher_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($teachers as $teacher)
                            @php
                                $isBusy = false;
                                if ($request->start_date && $request->end_date) {
                                    $isBusy = \App\Models\Booking::where('teacher_id', $teacher->id)
                                        ->where(function ($query) use ($request) {
                                            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                                                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                                                  ->orWhere(function ($query) use ($request) {
                                                      $query->where('start_date', '<=', $request->start_date)
                                                            ->where('end_date', '>=', $request->end_date);
                                                  });
                                        })
                                        ->where('id', '!=', $request->booking_id ?? null)
                                        ->exists();
                                }
                            @endphp
                            <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $request->teacher_id) == $teacher->id ? 'selected' : '' }}
                                    {{ $isBusy ? 'disabled style="color:#EF4444;"' : '' }}>
                                {{ $teacher->fio }}@if($isBusy) — ЗАНЯТ @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Куратор группы -->
                <div class="suzipo-col-1">
                    <label for="curator_id" class="suzipo-label">Куратор группы</label>
                    <select name="curator_id" id="curator_id" class="suzipo-field">
                        <option value="">---</option>
                        @foreach($curators as $curator)
                            <option value="{{ $curator->id }}" {{ old('curator_id', $request->curator_id) == $curator->id ? 'selected' : '' }}>
                                {{ $curator->fio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Резерв -->
                <div class="suzipo-col-1">
                    <label for="reserve" class="suzipo-label">Резерв</label>
                    <input type="text" name="reserve" id="reserve" value="{{ old('reserve', $request->reserve) }}"
                           class="suzipo-field" style="opacity: 0.7; cursor: not-allowed;" readonly>
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
                    Сохранить и закрыть
                </button>
            </div>
        </form>
    </div>
<script>

function editProviderSelector() {
    return {
        open: false,
        search: '',
        selectedId: '{{ old('provider_id', $request->provider_id) }}',
        selectedName: '',
        newProviderName: '',
        providers: [
            @foreach($providers as $provider)
                {id: '{{ $provider->id }}', name: '{{ addslashes($provider->name) }}'},
            @endforeach
        ],
        get filteredProviders() {
            if (!this.search) return this.providers;
            return this.providers.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        get isExistingProvider() {
            if (!this.search) return false;
            return this.providers.some(p => p.name.toLowerCase() === this.search.toLowerCase());
        },
        selectProvider(p) { 
            this.selectedId = p.id; 
            this.selectedName = p.name; 
            this.newProviderName = ''; 
            this.open = false; 
            this.search = ''; 
            
            // Отправляем событие для загрузки курсов
            document.dispatchEvent(new CustomEvent('provider-selected', {
                detail: { providerId: p.id }
            }));
        },
        addNewProvider() {
            if (!this.search) return;
            const existing = this.providers.find(p => p.name.toLowerCase() === this.search.toLowerCase());
            if (existing) { this.selectProvider(existing); return; }
            this.newProviderName = this.search; 
            this.selectedName = this.search; 
            this.selectedId = ''; 
            this.open = false;
            
            document.dispatchEvent(new CustomEvent('provider-selected', {
                detail: { providerId: null }
            }));
        },
        
        // Закрываем выпадающий список при открытии модального окна
        init() {
            document.addEventListener('close-provider-dropdown', () => {
                this.open = false;
            });

            
            // Если есть выбранный провайдер — показываем его название
            if (this.selectedId && this.selectedId !== '') {
                const s = this.providers.find(p => p.id == this.selectedId);
                if (s) {
                    this.selectedName = s.name;
                    // Отправляем событие для загрузки курсов
                    document.dispatchEvent(new CustomEvent('provider-selected', {
                        detail: { providerId: this.selectedId }
                    }));
                }
            }
        }
    }
}


function editCourseSelector() {
    return {
        open: false,
        search: '',
        selectedId: '{{ old('course_id', $request->course_id) }}',
        selectedName: '',
        newCourseName: '',
        courses: [],
        loading: false,
        providerSelected: false,
        providerId: null,
        message: '--- Выберите или введите курс ---',
	courseInfo: {},        
	courseInfoMap: {},     
        
        showMatrixModal: false,
        pendingCourseName: '',
        selectedMatrix: null,
        courseMatrixNums: {},
        selectedMatrixNum: '{{ $request->matrix_num }}' || null,
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
    this.providerId = providerId;
    this.isRnYuganskProvider = [91, 92].includes(Number(providerId));
    
    this.loading = true;
    this.open = true;
    this.search = '';
    this.courses = [];
    this.courseMatrixNums = {};
    
    try {
        const response = await fetch(`/api/courses-by-provider?provider_id=${providerId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        this.courses = data.courses.map(c => c.name);
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
        
        this.providerSelected = true;
    } catch (error) {
        console.error('Ошибка:', error);
        this.courses = [];
        this.providerSelected = false;
    } finally {
        this.loading = false;
    }
},        
        selectCourse(course) {
            this.selectedName = course;
            this.selectedId = null;
            this.newCourseName = course;
            this.selectedMatrixNum = this.courseMatrixNums[course] || this.selectedMatrixNum;
	    this.courseInfo = this.courseInfoMap[course] || {};
            this.open = false;
            this.search = '';
        },
        
        addNewCourse() {
            if (!this.search) return;
            
            const existing = this.courses.find(c => c.toLowerCase() === this.search.toLowerCase());
            if (existing) {
                this.selectCourse(existing);
                return;
            }
            
            if (this.isRnYuganskProvider) {
                this.pendingCourseName = this.search;
                this.newCourseName = this.search;
                this.selectedMatrix = null;
                this.showMatrixModal = true;
                this.open = false;
            } else {
                this.newCourseName = this.search;
                this.saveCourseToUrp(this.search);
                this.open = false;
            }
        },
        
        async saveSelectedMatrix() {
            if (!this.selectedMatrix) {
                alert('Пожалуйста, выберите матрицу');
                return;
            }
            await this.saveCourseToMatrix(this.selectedMatrix);
        },
        
        async saveCourseToUrp(name) {
            try {
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
                
                if (data.success) {
                    this.courses.push(name);
                    this.courses.sort();
                    
                    this.selectedName = name;
                    this.newCourseName = name;
                    this.selectedMatrixNum = data.matrix_num;
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
                
                if (data.success) {
                    this.courses.push(this.pendingCourseName);
                    this.courses.sort();
                    
                    this.selectedName = this.pendingCourseName;
                    this.newCourseName = this.pendingCourseName;
                    this.selectedMatrixNum = data.matrix_num;
                    this.selectedMatrix = matrix;
                    this.selectedId = null;
                    
                    this.showMatrixModal = false;
                    this.pendingCourseName = '';
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
    document.addEventListener('provider-selected', (event) => {
        if (event.detail.providerId) {
            this.loadCourses(event.detail.providerId);

        } else {
            this.reset();
        }
    });
    
    document.addEventListener('close-provider-dropdown', () => {
        this.open = false;
    });
    
    // Если уже есть выбранный провайдер — загружаем курсы
    const currentProviderId = '{{ $request->provider_id }}';
    if (currentProviderId) {
        this.loadCourses(Number(currentProviderId)).then(() => {
            // После загрузки курсов устанавливаем выбранный курс
            const currentCourseName = '{{ addslashes($request->course->course ?? $request->new_course_name ?? '') }}';
            const courseId = '{{ $request->course_id }}';
            const currentMatrixNum = '{{ $request->matrix_num }}';
            
            if (currentCourseName) {
                // Устанавливаем выбранный курс
                this.selectedName = currentCourseName;
                this.newCourseName = currentCourseName;
                this.selectedId = courseId || null;
                
                // Устанавливаем matrix_num
                if (this.courseMatrixNums[currentCourseName]) {
                    this.selectedMatrixNum = this.courseMatrixNums[currentCourseName];
                } else if (currentMatrixNum) {
                    this.selectedMatrixNum = currentMatrixNum;
                }
                this.courseInfo = this.courseInfoMap[currentCourseName] || {};
                // Закрываем выпадающий список
                this.open = false;
            }
        });
    }
}
    }
}

    function editCitySelector() {
        return {
            open: false,
            search: '',
            selectedId: '{{ old('city_id', $request->city_id) }}',
            selectedName: '',
            newCityName: '',
            cities: [
                @foreach($cities as $city)
                    {id: '{{ $city->id }}', name: '{{ addslashes($city->city) }}'},
                @endforeach
            ],
            get filteredCities() {
                if (!this.search) return this.cities;
                return this.cities.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
            },
            get isExistingCity() {
                if (!this.search) return false;
                return this.cities.some(c => c.name.toLowerCase() === this.search.toLowerCase());
            },
            selectCity(c) { 
                this.selectedId = c.id; 
                this.selectedName = c.name; 
                this.newCityName = ''; 
                this.open = false; 
                this.search = ''; 
            },
            addNewCity() {
                if (!this.search) return;
                const existing = this.cities.find(c => c.name.toLowerCase() === this.search.toLowerCase());
                if (existing) { this.selectCity(existing); return; }
                this.newCityName = this.search; 
                this.selectedName = this.search; 
                this.selectedId = ''; 
                this.open = false;
            },
            init() {
                if (this.selectedId) {
                    const s = this.cities.find(c => c.id == this.selectedId);
                    if (s) this.selectedName = s.name;
                }
                if (!this.selectedId && this.cities.length > 0) {
                    const def = this.cities.find(c => c.name.toLowerCase().includes('нефтеюганск'));
                    if (def) { this.selectedId = def.id; this.selectedName = def.name; }
                }
            }
        }
    }

    function editProfessionSelector() {
        return {
            open: false,
            search: '',
            selectedId: '{{ old('profession_id', $request->profession_id) }}',
            selectedName: '',
            newProfessionName: '',
            professions: [
                @foreach($professions as $p)
                    {id: '{{ $p->id }}', name: '{{ addslashes($p->name) }}'},
                @endforeach
            ],
            get filteredProfessions() {
                if (!this.search) return this.professions;
                return this.professions.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
            },
            get isExistingProfession() {
                if (!this.search) return false;
                return this.professions.some(p => p.name.toLowerCase() === this.search.toLowerCase());
            },
            selectProfession(p) { 
                this.selectedId = p.id; 
                this.selectedName = p.name; 
                this.newProfessionName = ''; 
                this.open = false; 
                this.search = ''; 
            },
            addNewProfession() {
                if (!this.search) return;
                const existing = this.professions.find(p => p.name.toLowerCase() === this.search.toLowerCase());
                if (existing) { this.selectProfession(existing); return; }
                this.newProfessionName = this.search; 
                this.selectedName = this.search; 
                this.selectedId = ''; 
                this.open = false;
            },
            init() {
                if (this.selectedId) {
                    const s = this.professions.find(p => p.id == this.selectedId);
                    if (s) this.selectedName = s.name;
                }
            }
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const audienceSelect = document.getElementById('audience_id');
    const teacherSelect = document.getElementById('teacher_id');

    // Функция для обновления доступных аудиторий
    async function updateAudiences() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) return;

        const response = await fetch(`/api/available-audiences?start_date=${startDate}&end_date=${endDate}`);
        const bookedAudienceIds = await response.json();

        // Сохраняем выбранное значение
        const currentValue = audienceSelect.value;

        // Перебираем все опции
        Array.from(audienceSelect.options).forEach(option => {
            const audienceId = option.value;
            if (audienceId && bookedAudienceIds.includes(parseInt(audienceId))) {
                option.disabled = true;
		option.textContent = option.textContent.replace(' - ЗАНЯТА', '').trim() + ' - ЗАНЯТА';
                option.classList.add('text-red-500');
            } else {
                option.disabled = false;
                // Убираем пометку "ЗАНЯТА"
	        option.textContent = option.textContent.replace(' - ЗАНЯТА', '').trim();
                option.classList.remove('text-red-500');
            }
        });

        // Восстанавливаем выбранное значение, если оно доступно
        if (currentValue && !bookedAudienceIds.includes(parseInt(currentValue))) {
            audienceSelect.value = currentValue;
        }
    }

    // Функция для обновления доступных преподавателей
    async function updateTeachers() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) return;

        const response = await fetch(`/api/available-teachers?start_date=${startDate}&end_date=${endDate}`);
        const bookedTeacherIds = await response.json();

        // Сохраняем выбранное значение
        const currentValue = teacherSelect.value;

        // Перебираем все опции
        Array.from(teacherSelect.options).forEach(option => {
            const teacherId = option.value;
            if (teacherId && bookedTeacherIds.includes(parseInt(teacherId))) {
                option.disabled = true;
		option.textContent = option.textContent.replace(' - ЗАНЯТ', '').trim() + ' - ЗАНЯТ';
                option.classList.add('text-red-500');
            } else {
                option.disabled = false;
		option.textContent = option.textContent.replace(' - ЗАНЯТ', '').trim();
                option.classList.remove('text-red-500');
            }
        });

        // Восстанавливаем выбранное значение, если оно доступно
        if (currentValue && !bookedTeacherIds.includes(parseInt(currentValue))) {
            teacherSelect.value = currentValue;
        }
    }

    // Слушаем изменения дат
    startDateInput.addEventListener('change', function() {
        updateAudiences();
        updateTeachers();
    });

    endDateInput.addEventListener('change', function() {
        updateAudiences();
        updateTeachers();
    });

    // Обновляем при загрузке страницы
    if (startDateInput.value && endDateInput.value) {
        updateAudiences();
        updateTeachers();
    }
});

// Функция для обновления резерва
function updateReserve() {
    const audienceSelect = document.getElementById('audience_id');
    const reserveInput = document.getElementById('reserve');
    const selectedAudienceId = audienceSelect.value;
    
    if (!selectedAudienceId) {
        reserveInput.value = '';
        return;
    }
    
    // Находим выбранную аудиторию
    const selectedOption = audienceSelect.options[audienceSelect.selectedIndex];
    const seats = selectedOption.dataset.seats ? parseInt(selectedOption.dataset.seats) : 0;
    
    // Если seats не заполнено (ДОТ) - резерв пустой
    if (!seats || seats === 0) {
        reserveInput.value = '';
        return;
    }
    
    // Получаем количество сотрудников через AJAX
    fetch(`/api/request-employees-count/{{ $request->id }}`)
        .then(response => response.json())
        .then(data => {
            const employeeCount = data.count || 0;
            const reserve = seats - employeeCount;
            reserveInput.value = reserve;
        });
}

// Слушаем изменение аудитории
document.getElementById('audience_id').addEventListener('change', updateReserve);

// Обновляем при загрузке
updateReserve();
</script>
</x-layouts.app-with-sidebar>

