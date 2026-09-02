<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Создание новой заявки</h2>

                <form action="{{ route('requests.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Одноразовая заявка -->
                        <div class="col-span-full">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="one_time" value="1" {{ old('one_time') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Одноразовая заявка</span>
                            </label>
                        </div>

                        <!-- Дата начала обучения -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Дата начала обучения</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Дата окончания обучения -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Дата окончания обучения</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Дата оформления -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-1">Дата оформления</label>
                            <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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

<!-- Учебное заведение (провайдер) -->
<div x-data="providerSelector()" class="relative">
    <label class="block text-sm font-medium text-gray-700 mb-1">Учебное заведение (провайдер)</label>
    
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
                this.selectedId = provider.id;
                this.selectedName = provider.name;
                this.newProviderName = '';
                this.open = false;
                this.search = '';
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
            },
            init() {
                if (this.selectedId) {
                    const selected = this.providers.find(p => p.id == this.selectedId);
                    if (selected) {
                        this.selectedName = selected.name;
                    }
                }
            }
        }
    }
</script>
<!-- Наименование курса (тематика) -->
<div x-data="courseSelector()" class="relative">
    <label class="block text-sm font-medium text-gray-700 mb-1">Наименование курса (тематика)</label>
    
    <input type="hidden" name="course_id" x-model="selectedId">
    <input type="hidden" name="new_course_name" x-model="newCourseName">
    
    <div @click="open = !open; if(!open) search = ''" 
         class="w-full px-3 py-2 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white min-h-[38px]">
        <span x-text="selectedName || '--- Выберите или введите курс ---'" 
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
            
            <template x-for="course in filteredCourses" :key="course.id">
                <div @click="selectCourse(course)"
                     class="px-3 py-2 cursor-pointer hover:bg-indigo-50 text-sm border-b border-gray-100 last:border-b-0"
                     :class="{'bg-indigo-100': selectedId == course.id}"
                     style="white-space: normal; word-wrap: break-word; line-height: 1.4;">
                    <span x-text="course.name"></span>
                </div>
            </template>
            
            <div x-show="filteredCourses.length === 0 && !search" class="px-3 py-4 text-sm text-gray-400 text-center">
                Начните вводить название курса
            </div>
        </div>
    </div>
</div>

<script>
    function courseSelector() {
        return {
            open: false,
            search: '',
            selectedId: '{{ old('course_id') }}',
            selectedName: '',
            newCourseName: '',
            courses: [
                @foreach($courses as $course)
                    {id: '{{ $course->id }}', name: '{{ addslashes($course->course) }}'},
                @endforeach
            ],
            get filteredCourses() {
                if (!this.search) return this.courses;
                return this.courses.filter(c => 
                    c.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },
            get isExistingCourse() {
                if (!this.search) return false;
                return this.courses.some(c => c.name.toLowerCase() === this.search.toLowerCase());
            },
            selectCourse(course) {
                this.selectedId = course.id;
                this.selectedName = course.name;
                this.newCourseName = '';
                this.open = false;
                this.search = '';
            },
            addNewCourse() {
                if (!this.search) return;
                
                // Проверяем, существует ли уже такой курс
                const existing = this.courses.find(c => c.name.toLowerCase() === this.search.toLowerCase());
                if (existing) {
                    this.selectCourse(existing);
                    return;
                }
                
                // Сохраняем название нового курса
                this.newCourseName = this.search;
                this.selectedName = this.search;
                this.selectedId = '';
                this.open = false;
                
                // Можно сразу отправить форму или показать сообщение
                alert('Новый курс "' + this.search + '" будет создан при сохранении заявки.');
            },
            init() {
                if (this.selectedId) {
                    const selected = this.courses.find(c => c.id == this.selectedId);
                    if (selected) {
                        this.selectedName = selected.name;
                    }
                }
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
            selectedId: '{{ old('city_id', $defaultCityId ?? '') }}',
            selectedName: '',
            newCityName: '',
            cities: [
                @foreach($cities as $city)
                    {id: '{{ $city->id }}', name: '{{ addslashes($city->city) }}'},
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
            selectedId: '{{ old('profession_id') }}',
            selectedName: '',
            newProfessionName: '',
            professions: [
                @foreach($professions as $profession)
                    {id: '{{ $profession->id }}', name: '{{ addslashes($profession->name) }}'},
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
                        <a href="{{ route('requests.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить заявку</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-layouts.app-with-sidebar>