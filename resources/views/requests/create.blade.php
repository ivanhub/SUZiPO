<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Создание новой заявки</h2>

                <form action="{{ route('requests.store') }}" method="POST">
                    @csrf

                    <!-- Ультракомпактный однострочный контейнер для префикса и номера -->
                    <div class="col-span-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-center border-b border-gray-100 pb-4 mb-2">

                        <!-- 1. Селектор префикса -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-0.5">Тип заявки (Префикс)</label>
                            @if($isUrp)
                            <div class="w-full px-2.5 py-1 border border-gray-300 rounded-md bg-gray-50 text-gray-700 text-xs h-[32px] flex items-center font-medium">
                                ЮНГ
                            </div>
                            <input type="hidden" name="req_prefix" value="ЮНГ">
                            @else
                            <select name="req_prefix" onchange="updateRequestNumber(this.value)"
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-xs h-[32px]">
                                <option value="ЮЛ" {{ old('req_prefix') == 'ЮЛ' ? 'selected' : '' }}>ЮЛ</option>
                                <option value="ФЛ" {{ old('req_prefix') == 'ФЛ' ? 'selected' : '' }}>ФЛ</option>
                            </select>
                            @endif
                        </div>

                        <!-- 2. Поле формируемого номера -->
                        <div>
                            <label for="display_req_id" class="block text-xs font-medium text-gray-500 mb-0.5">Формируемый номер заявки</label>
                            <input type="text" id="display_req_id" readonly
                                value="{{ $nextPrefixNumbers[$isUrp ? 'ЮНГ' : 'ЮЛ'] }}-{{ $isUrp ? 'ЮНГ' : 'ЮЛ' }}"
                                class="w-full px-2.5 py-1 border border-gray-300 rounded-md bg-gray-50 font-bold text-indigo-700 cursor-not-allowed text-xs h-[32px] tracking-wide shadow-sm">
                        </div>
                    </div>

                    <script>
                        function updateRequestNumber(selectedPrefix) {
                            const nextPrefixNumbers = @json($nextPrefixNumbers);
                            const numberInput = document.getElementById('display_req_id');
                            if (numberInput && nextPrefixNumbers[selectedPrefix]) {
                                numberInput.value = nextPrefixNumbers[selectedPrefix] + '-' + selectedPrefix;
                            }
                        }
                    </script>


                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Одноразовая заявка -->
                        <div class="col-span-full">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="one_time" value="1" {{ old('one_time') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Одноразовая заявка</span>
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
<!-- <input type="hidden" name="req_prefix" value="ЮНГ">
-->
@endif

<!-- Дата начала обучения -->
<div class="relative">
    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Дата начала обучения</label>
    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent date-input">
    <span class="absolute left-3 top-[36px] text-gray-400 pointer-events-none date-placeholder">ДД.ММ.ГГГГ</span>
    @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Дата окончания обучения -->
<div class="relative">
    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Дата окончания обучения</label>
    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent date-input">
    <span class="absolute left-3 top-[36px] text-gray-400 pointer-events-none date-placeholder">ДД.ММ.ГГГГ</span>
    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Дата оформления -->
<div class="relative">
    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-1">Дата оформления</label>
    <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date') }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent date-input">
    <span class="absolute left-3 top-[36px] text-gray-400 pointer-events-none date-placeholder">ДД.ММ.ГГГГ</span>
    @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

                        <!-- Форма образования -->
                        <div>
                            <label for="education_form" class="block text-sm font-medium text-gray-700 mb-1">Форма образования</label>
                            <select name="education_form" id="education_form"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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

                        <!-- ИТР/рабочие -->
                        <div>
                            <label for="employee_type" class="block text-sm font-medium text-gray-700 mb-1">ИТР/рабочие</label>
                            <select name="employee_type" id="employee_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                <option value="ИТР(РСС)" {{ old('employee_type') == 'ИТР(РСС)' ? 'selected' : '' }}>ИТР(РСС)</option>
                                <option value="Рабочие" {{ old('employee_type') == 'Рабочие' ? 'selected' : '' }}>Рабочие</option>
                            </select>
                        </div>

                        <!-- С отрывом от производства -->
                        <div>
                            <label for="production_break" class="block text-sm font-medium text-gray-700 mb-1">С отрывом от производства</label>
                            <select name="production_break" id="production_break"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                <option value="с отрывом от производства" {{ old('production_break') == 'с отрывом от производства' ? 'selected' : '' }}>С отрывом от производства</option>
                                <option value="без отрыва от производства" {{ old('production_break') == 'без отрыва от производства' ? 'selected' : '' }}>Без отрыва от производства</option>
                                <option value="без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий" {{ old('production_break') == 'без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий' ? 'selected' : '' }}>Без отрыва от производства с применением электронного обучения и дистанционных образовательных технологий</option>
                            </select>
                        </div>

<!-- Учебное заведение -->
<div x-data="providerSelector()" class="relative z-10">
    <label class="block text-sm font-medium text-gray-700 mb-1">Учебное заведение</label>
    
    <input type="hidden" name="provider_id" x-model="selectedId">
    <input type="hidden" name="new_provider_name" x-model="newProviderName">
    
    <div @click="open = !open; if(!open) search = ''" 
         class="w-full px-3 py-2 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white min-h-[38px]">
        <span x-text="selectedName || '--- Выберите или введите провайдера ---'" 
              class="text-sm" 
              :class="{'text-gray-400': !selectedName}"></span>
    </div>
    
    <div x-show="open" 
         @click.away="open = false"
         class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
         style="max-height: 450px; display: flex; flex-direction: column;">
        
        <div class="sticky top-0 bg-white border-b border-gray-200 p-2 flex-shrink-0">
            <input type="text" 
                   x-model="search"
                   placeholder="Поиск по провайдерам или введите новый..."
                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
                   @click.stop
                   @keydown.enter.prevent="addNewProvider()">
        </div>
        
        <div class="overflow-y-auto flex-1" style="max-height: 350px;">
            <div x-show="search && !isExistingProvider" 
                 @click="addNewProvider()"
                 class="px-3 py-3 cursor-pointer hover:bg-green-50 text-sm border-b border-green-200 bg-green-50 flex items-center">
                <span class="text-green-600 font-medium mr-2">+</span>
                <span>Добавить нового провайдера: "<span x-text="search" class="font-semibold"></span>"</span>
            </div>
            
            <template x-for="provider in filteredProviders" :key="provider.id">
                <div @click="selectProvider(provider)"
                     class="px-3 py-2 cursor-pointer hover:bg-indigo-50 text-sm border-b border-gray-100 last:border-b-0"
                     :class="{'bg-indigo-100': selectedId == provider.id}"
                     style="white-space: normal; word-wrap: break-word; line-height: 1.4;">
                    <span x-text="provider.name"></span>
                </div>
            </template>
            
            <div x-show="filteredProviders.length === 0 && !search" class="px-3 py-4 text-sm text-gray-400 text-center">
                Начните вводить название провайдера
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
                @foreach($providers as $provider)
                    {id: '{{ $provider->id }}', name: '{{ addslashes($provider->name) }}'},
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
                    detail: { providerId: provider.id }
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
                    detail: { providerId: null }
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
                            detail: { providerId: selected.id }
                        }));
                    }
                }
            }
        }
    }
</script>


<!-- Наименование курса -->
<div x-data="courseSelector()" class="relative z-30">
    <label class="block text-sm font-medium text-gray-700 mb-1">Наименование курса</label>
    
    <input type="hidden" name="course_id" x-model="selectedId">
    <input type="hidden" name="new_course_name" x-model="newCourseName">
    <input type="hidden" name="matrix_num" x-model="selectedMatrixNum">
    
    <div @click="open = !open; if(!open) search = ''" 
         class="w-full px-3 py-2 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white min-h-[38px]">
        <span x-text="selectedName || message" 
              class="text-sm" 
              :class="{'': !selectedName && !providerSelected}"></span>
    </div>
    
    <div x-show="open" 
         @click.away="open = false"
         class="course-dropdown absolute z-30 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
         style="max-height: 450px; display: flex; flex-direction: column;">
        
        <!-- Если провайдер не выбран -->
        <template x-if="!providerSelected && !loading">
            <div class="px-6 py-8 text-center">
                <p class="text-base font-bold text-gray-700">Выберите учебное заведение</p>
            </div>
        </template>
        
        <!-- Если загрузка -->
        <template x-if="loading">
            <div class="px-6 py-8 text-center">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Загрузка курсов...</span>
                </div>
            </div>
        </template>
        
        <!-- Если провайдер выбран и курсы не найдены -->
        <template x-if="providerSelected && !loading && courses.length === 0">
            <div class="px-6 py-8 text-center text-gray-500">
                Курсы не найдены
            </div>
        </template>

        <!-- Если провайдер выбран и есть курсы -->
        <template x-if="providerSelected && !loading && courses.length > 0">
            <div>
                <div class="sticky top-0 bg-white border-b border-gray-200 p-2 flex-shrink-0">
                    <input type="text" 
                           x-model="search"
                           placeholder="Поиск по курсам или введите новый..."
                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
                           @click.stop
                           @keydown.enter.prevent="addNewCourse()">
                </div>
                
                <div class="overflow-y-auto flex-1" style="max-height: 350px;">
                    <!-- Кнопка "Добавить новый курс" -->
                    <div x-show="search && !isExistingCourse" 
                         @click="addNewCourse()"
                         class="px-3 py-3 cursor-pointer hover:bg-green-50 text-sm border-b border-green-200 bg-green-50 flex items-center"
                         style="white-space: normal; word-wrap: break-word; line-height: 1.4;">
                        <span class="text-green-600 font-medium mr-2">+</span>
                        <span>Добавить новый курс: "<span x-text="search" class="font-semibold"></span>"</span>
                    </div>
                    
                    <template x-for="course in filteredCourses" :key="course">
                        <div @click="selectCourse(course)"
                             class="px-3 py-2 cursor-pointer hover:bg-indigo-50 text-sm border-b border-gray-100 last:border-b-0"
                             :class="{'bg-indigo-100': selectedName === course}"
                             style="white-space: normal; word-wrap: break-word; line-height: 1.4;">
                            <span x-text="course"></span>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

{{-- МОДАЛЬНОЕ ОКНО С x-teleport --}}
<template x-teleport="body">
    <div x-show="showMatrixModal"
         x-cloak
         class="fixed inset-0 z-[99999] overflow-y-auto"
         @click="showMatrixModal = false; open = false; $dispatch('close-provider-dropdown')"
         style="display: none;">
            
            <div class="flex items-center justify-center min-h-screen px-4">
                {{-- Подложка --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="showMatrixModal = false"></div>
                
                {{-- Само окно --}}
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto p-6" @click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Добавление нового курса
                        </h3>
                        <button @click="showMatrixModal = false"
                                class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">
                        Выберите матрицу для добавления курса в справочник:
                    </p>
                    
                    <div class="text-sm font-semibold text-gray-800 bg-gray-100 rounded p-3 mb-4">
                        <span x-text="pendingCourseName"></span>
                    </div>
                    
                    {{-- Выбор матрицы через radio --}}
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-indigo-50 transition">
                            <input type="radio" 
                                   name="matrix_select" 
                                   value="matrix_courses" 
                                   x-model="selectedMatrix"
                                   class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">Matrix Courses</span>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-green-50 transition">
                            <input type="radio" 
                                   name="matrix_select" 
                                   value="matrix_dpo" 
                                   x-model="selectedMatrix"
                                   class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">Matrix DPO</span>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-amber-50 transition">
                            <input type="radio" 
                                   name="matrix_select" 
                                   value="matrix_ot" 
                                   x-model="selectedMatrix"
                                   class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">Matrix OT</span>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-purple-50 transition">
                            <input type="radio" 
                                   name="matrix_select" 
                                   value="matrix_po" 
                                   x-model="selectedMatrix"
                                   class="h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">Matrix PO</span>
                        </label>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-2">
                        <button @click="showMatrixModal = false"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Отмена
                        </button>
                        <button @click="saveSelectedMatrix(); open = false; $dispatch('close-provider-dropdown')"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                                :disabled="!selectedMatrix">
                            Сохранить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
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
        
        // Флаги для модального окна
        showMatrixModal: false,
        pendingCourseName: '',
        selectedMatrix: null,  // Выбранная матрица
	courseMatrixNums: {},  // 
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
	    this.courseMatrixNums = {};  // ← Добавляем объект для хранения matrix_num
            
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
        data.courses.forEach(c => {
            this.courseMatrixNums[c.name] = c.matrix_num;
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
	    this.newCourseName = course;  // ← Сохраняем название курса
	    this.selectedMatrixNum = this.courseMatrixNums[course] || null;  // ← Устанавливаем matrix_num
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
        this.newCourseName = this.search;  // ← Сохраняем название
        this.selectedMatrix = null;  // ← Сбрасываем выбранную матрицу
        this.showMatrixModal = true;
        this.open = false;  // ← Закрываем выпадающий список
    } else {
        // Иначе сразу сохраняем в courses_urp
        console.log('Сохраняем в courses_urp');
        this.newCourseName = this.search;  // ← Сохраняем название
        this.saveCourseToUrp(this.search);
        this.open = false;  // ← Закрываем выпадающий список
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
            this.newCourseName = name;  // ← Сохраняем название
            this.selectedMatrixNum = data.matrix_num;  // ← Сохраняем matrix_num
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
            this.newCourseName = this.pendingCourseName;  // ← Сохраняем название
            this.selectedMatrixNum = data.matrix_num;  // ← Сохраняем matrix_num
            this.selectedMatrix = matrix;
     console.log('selectedMatrixNum:', this.selectedMatrixNum);
            this.selectedId = null;
            
            // Закрываем модальное окно
            this.showMatrixModal = false;
            this.pendingCourseName = '';
            this.open = false;  // ← Закрываем выпадающий список
           
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
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Место проведения (страна)</label>
                            <input type="text" name="country" id="country" value="{{ old('country', 'Россия') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Место проведения (город) -->
                        <div x-data="citySelector()" class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Место проведения (город)</label>

                            <input type="hidden" name="city_id" x-model="selectedId">
                            <input type="hidden" name="new_city_name" x-model="newCityName">

                            <div @click="open = !open; if(!open) search = ''"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white min-h-[38px]">
                                <span x-text="selectedName || '--- Выберите или введите город ---'"
                                    class="text-sm"
                                    :class="{'text-gray-400': !selectedName}"></span>
                            </div>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
                                style="max-height: 400px; display: flex; flex-direction: column;">

                                <div class="sticky top-0 bg-white border-b border-gray-200 p-2 flex-shrink-0">
                                    <input type="text"
                                        x-model="search"
                                        placeholder="Поиск по городам или введите новый..."
                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        @click.stop
                                        @keydown.enter.prevent="addNewCity()">
                                </div>

                                <div class="overflow-y-auto flex-1" style="max-height: 350px;">
                                    <div x-show="search && !isExistingCity"
                                        @click="addNewCity()"
                                        class="px-3 py-3 cursor-pointer hover:bg-green-50 text-sm border-b border-green-200 bg-green-50 flex items-center">
                                        <span class="text-green-600 font-medium mr-2">+</span>
                                        <span>Добавить новый город: "<span x-text="search" class="font-semibold"></span>"</span>
                                    </div>

                                    <template x-for="city in filteredCities" :key="city.id">
                                        <div @click="selectCity(city)"
                                            class="px-3 py-2 cursor-pointer hover:bg-indigo-50 text-sm border-b border-gray-100 last:border-b-0"
                                            :class="{'bg-indigo-100': selectedId == city.id}"
                                            style="white-space: normal; word-wrap: break-word; line-height: 1.4;">
                                            <span x-text="city.name"></span>
                                        </div>
                                    </template>

                                    <div x-show="filteredCities.length === 0 && !search" class="px-3 py-4 text-sm text-gray-400 text-center">
                                        Начните вводить название города
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function citySelector() {
                                return {
                                    open: false,
                                    search: '',
                                    selectedId: '{{ old('
                                    city_id ', $defaultCityId ?? '
                                    ') }}',
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
                        <div x-data="professionSelector()" class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Профессия, присваиваемая по результатам обучения</label>

                            <input type="hidden" name="profession_id" x-model="selectedId">
                            <input type="hidden" name="new_profession_name" x-model="newProfessionName">

                            <div @click="open = !open; if(!open) search = ''"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white min-h-[38px]">
                                <span x-text="selectedName || '--- Выберите или введите профессию ---'"
                                    class="text-sm"
                                    :class="{'text-gray-400': !selectedName}"></span>
                            </div>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
                                style="max-height: 400px; display: flex; flex-direction: column;">

                                <div class="sticky top-0 bg-white border-b border-gray-200 p-2 flex-shrink-0">
                                    <input type="text"
                                        x-model="search"
                                        placeholder="Поиск по профессиям или введите новую..."
                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        @click.stop
                                        @keydown.enter.prevent="addNewProfession()">
                                </div>

                                <div class="overflow-y-auto flex-1" style="max-height: 350px;">
                                    <div x-show="search && !isExistingProfession"
                                        @click="addNewProfession()"
                                        class="px-3 py-3 cursor-pointer hover:bg-green-50 text-sm border-b border-green-200 bg-green-50 flex items-center">
                                        <span class="text-green-600 font-medium mr-2">+</span>
                                        <span>Добавить новую профессию: "<span x-text="search" class="font-semibold"></span>"</span>
                                    </div>

                                    <template x-for="profession in filteredProfessions" :key="profession.id">
                                        <div @click="selectProfession(profession)"
                                            class="px-3 py-2 cursor-pointer hover:bg-indigo-50 text-sm border-b border-gray-100 last:border-b-0"
                                            :class="{'bg-indigo-100': selectedId == profession.id}"
                                            style="white-space: normal; word-wrap: break-word; line-height: 1.4;">
                                            <span x-text="profession.name"></span>
                                        </div>
                                    </template>

                                    <div x-show="filteredProfessions.length === 0 && !search" class="px-3 py-4 text-sm text-gray-400 text-center">
                                        Начните вводить название профессии
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function professionSelector() {
                                return {
                                    open: false,
                                    search: '',
                                    selectedId: '{{ old('
                                    profession_id ') }}',
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
                        <div>
                            <label for="learn_reason_id" class="block text-sm font-medium text-gray-700 mb-1">Причина обучения</label>
                            <select name="learn_reason_id" id="learn_reason_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                @foreach($learnReasons as $reason)
                                <option value="{{ $reason->id }}" {{ old('learn_reason_id') == $reason->id ? 'selected' : '' }}>{{ $reason->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ресурс обучения/оценки -->
                        <div>
                            <label for="learning_resource_id" class="block text-sm font-medium text-gray-700 mb-1">Ресурс обучения/оценки</label>
                            <select name="learning_resource_id" id="learning_resource_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                @foreach($learningResources as $resource)
                                <option value="{{ $resource->id }}" {{ old('learning_resource_id') == $resource->id ? 'selected' : '' }}>{{ $resource->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Вид обучения/оценки -->
                        <div>
                            <label for="learning_type_id" class="block text-sm font-medium text-gray-700 mb-1">Вид обучения/оценки</label>
                            <select name="learning_type_id" id="learning_type_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                @foreach($learningTypes as $type)
                                <option value="{{ $type->id }}" {{ old('learning_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Вид мероприятия -->
                        <div>
                            <label for="event_type_id" class="block text-sm font-medium text-gray-700 mb-1">Вид мероприятия</label>
                            <select name="event_type_id" id="event_type_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                @foreach($eventsTypes as $eventType)
                                <option value="{{ $eventType->id }}" {{ old('event_type_id') == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Дисциплина -->
                        <div>
                            <label for="discipline_id" class="block text-sm font-medium text-gray-700 mb-1">Дисциплина</label>
                            <select name="discipline_id" id="discipline_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                @foreach($disciplines as $discipline)
                                <option value="{{ $discipline->id }}" {{ old('discipline_id') == $discipline->id ? 'selected' : '' }}>{{ $discipline->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Себестоимость/Прибыль -->
                        <div>
                            <label for="cost_profit" class="block text-sm font-medium text-gray-700 mb-1">Себестоимость/Прибыль</label>
                            <select name="cost_profit" id="cost_profit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">---</option>
                                <option value="Себестоимость" {{ old('cost_profit') == 'Себестоимость' ? 'selected' : '' }}>Себестоимость</option>
                                <option value="Прибыль" {{ old('cost_profit') == 'Прибыль' ? 'selected' : '' }}>Прибыль</option>
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end space-x-2 mt-8">
                        <a href="{{ route('requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>

                        <!-- Кнопка "Сохранить и добавить сотрудников" -->
                        <button type="submit" name="action" value="save_and_employees"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            Сохранить и добавить сотрудников
                        </button>

                        <!-- Кнопка "Сохранить заявку" -->
                        <button type="submit" name="action" value="save"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
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