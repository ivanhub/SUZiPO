<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Просмотр заявки #{{ $request->id }}</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Назад к списку</a>
                        <a href="{{ route('requests.edit', $request) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Статус -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Статус</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($request->status === 'draft') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'sent') bg-blue-100 text-blue-800
                                @elseif($request->status === 'accepted') bg-green-100 text-green-800
                                @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $request->status }}
                            </span>
                        </p>
                    </div>

                    <!-- Одноразовая заявка -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Одноразовая заявка</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->one_time ? 'Да' : 'Нет' }}</p>
                    </div>

                    <!-- Дата начала обучения -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дата начала обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->start_date ? $request->start_date->format('d.m.Y') : '—' }}</p>
                    </div>

                    <!-- Дата окончания обучения -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дата окончания обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->end_date ? $request->end_date->format('d.m.Y') : '—' }}</p>
                    </div>

                    <!-- Дата оформления -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дата оформления</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->issue_date ? $request->issue_date->format('d.m.Y') : '—' }}</p>
                    </div>

                    <!-- Форма образования -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Форма образования</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->education_form ?? '—' }}</p>
                    </div>

                    <!-- ИТР/рабочие -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">ИТР/рабочие</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->employee_type ?? '—' }}</p>
                    </div>

                    <!-- С отрывом от производства -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">С отрывом от производства</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->production_break ?? '—' }}</p>
                    </div>

                    <!-- Учебное заведение (провайдер) -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Учебное заведение (провайдер)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->provider->name ?? '—' }}</p>
                    </div>

                    <!-- Наименование курса -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Наименование курса (тематика)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->course->course ?? '—' }}</p>
                    </div>

                    <!-- Место проведения (страна) -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Место проведения (страна)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->country ?? '—' }}</p>
                    </div>

                    <!-- Место проведения (город) -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Место проведения (город)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->city->city ?? '—' }}</p>
                    </div>

                    <!-- Профессия -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Профессия, присваиваемая по результатам обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->profession->name ?? '—' }}</p>
                    </div>

                    <!-- Причина обучения -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Причина обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->learnReason->name ?? '—' }}</p>
                    </div>

                    <!-- Ресурс обучения/оценки -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Ресурс обучения/оценки</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->learningResource->name ?? '—' }}</p>
                    </div>

                    <!-- Вид обучения/оценки -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Вид обучения/оценки</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->learningType->name ?? '—' }}</p>
                    </div>

                    <!-- Вид мероприятия -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Вид мероприятия</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->eventType->name ?? '—' }}</p>
                    </div>

                    <!-- Дисциплина -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дисциплина</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->discipline->name ?? '—' }}</p>
                    </div>

                    <!-- Себестоимость/Прибыль -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Себестоимость/Прибыль</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->cost_profit ?? '—' }}</p>
                    </div>
                </div>

                <!-- Только для просмотра и редактирования -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Назначенные ресурсы (только для просмотра и редактирования)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Аудитория</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->audience->number ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">ФИО преподавателя</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->teacher->fio ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Куратор группы</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->curator->fio ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <a href="{{ route('requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Назад к списку</a>
                    <a href="{{ route('requests.edit', $request) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app-with-sidebar>