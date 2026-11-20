<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Блок успешных уведомлений -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    @if(session('request_id'))
                    <a href="{{ route('request-employees.index', session('request_id')) }}"
                        class="ml-4 inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition">
                        Добавить сотрудников →
                    </a>
                    @endif
                </div>
                @endif

                <!-- Блок уведомлений об ошибках -->
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <!-- Начало формы для массовой отправки -->
                <!-- Измененная Часть 1 -->
                <form id="mass-send-form" action="{{ route('requests.send-to-ooo') }}" method="POST">
                    @csrf

                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <h2 class="text-lg font-semibold text-gray-900">Заявки на обучение</h2>

@if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']))
<button type="submit" id="btn-mass-send"
    class="hidden inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition"
    onclick="return confirm('Отправить выбранные заявки в ООО?')">
    🚀 Отправить выбранные в ООО
</button>
@endif
                        </div>

                        <a href="{{ route('requests.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            + Создать заявку
                        </a>
                    </div>
                </form>
<!-- Фильтры -->
<div class="mb-6 p-4 bg-gray-50 rounded-lg">
    <form method="GET" action="{{ route('requests.index') }}" id="filterForm" class="space-y-4">
        <!-- Общий поиск -->
        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Поиск по всем полям..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="globalSearch"
                           autocomplete="off">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="width: 100%;">
<thead class="bg-gray-50">
    <!-- Строка с фильтрами (соответствует колонкам) -->
    <tr class="bg-gray-100">
        <th class="px-2 py-2">
            <!-- Фильтр для чекбокса -->
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_id" value="{{ request('filter_id') }}" 
                   placeholder="ID..." 
                   class="w-full px-1 py-1 text-xs border rounded"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_req_id" value="{{ request('filter_req_id') }}" 
                   placeholder="№..." 
                   class="w-full px-1 py-1 text-xs border rounded"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <select name="filter_status" class="w-full px-1 py-1 text-xs border rounded"
                    onchange="this.form.submit()">
                <option value="">Статус</option>
                <option value="Создана" {{ request('filter_status') == 'Создана' ? 'selected' : '' }}>Создана</option>
                <option value="Отправлена" {{ request('filter_status') == 'Отправлена' ? 'selected' : '' }}>Отправлена</option>
                <option value="in_progress" {{ request('filter_status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                <option value="urpedit" {{ request('filter_status') == 'urpedit' ? 'selected' : '' }}>На доработке</option>
                <option value="accepted" {{ request('filter_status') == 'accepted' ? 'selected' : '' }}>Принята</option>
                <option value="rejected" {{ request('filter_status') == 'rejected' ? 'selected' : '' }}>Отклонена</option>
            </select>
        </th>
        <th class="px-1 py-2">
            <select name="filter_one_time" class="w-full px-1 py-1 text-xs border rounded"
                    onchange="this.form.submit()">
                <option value="">Разов.</option>
                <option value="1" {{ request('filter_one_time') == '1' ? 'selected' : '' }}>Да</option>
                <option value="0" {{ request('filter_one_time') == '0' ? 'selected' : '' }}>Нет</option>
            </select>
        </th>
        <th class="px-1 py-2">
            <input type="date" name="filter_start_from" value="{{ request('filter_start_from') }}" 
                   class="w-full px-1 py-1 text-xs border rounded"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="date" name="filter_end_to" value="{{ request('filter_end_to') }}" 
                   class="w-full px-1 py-1 text-xs border rounded"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="date" name="filter_issue_date" value="{{ request('filter_issue_date') }}" 
                   class="w-full px-1 py-1 text-xs border rounded"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_course" value="{{ request('filter_course') }}" 
                   placeholder="Курс..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[80px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_provider" value="{{ request('filter_provider') }}" 
                   placeholder="Провайдер..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[80px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <select name="filter_form" class="w-full px-1 py-1 text-xs border rounded"
                    onchange="this.form.submit()">
                <option value="">Форма</option>
                <option value="дистанционное" {{ request('filter_form') == 'дистанционное' ? 'selected' : '' }}>Дист.</option>
                <option value="заочное" {{ request('filter_form') == 'заочное' ? 'selected' : '' }}>Заочн.</option>
                <option value="очное" {{ request('filter_form') == 'очное' ? 'selected' : '' }}>Очн.</option>
                <option value="очное-заочное (вечернее)" {{ request('filter_form') == 'очное-заочное (вечернее)' ? 'selected' : '' }}>Очн-заочн.</option>
            </select>
        </th>
        <th class="px-1 py-2">
            <select name="filter_employee_type" class="w-full px-1 py-1 text-xs border rounded"
                    onchange="this.form.submit()">
                <option value="">ИТР/раб</option>
                <option value="ИТР(РСС)" {{ request('filter_employee_type') == 'ИТР(РСС)' ? 'selected' : '' }}>ИТР</option>
                <option value="Рабочие" {{ request('filter_employee_type') == 'Рабочие' ? 'selected' : '' }}>Рабочие</option>
            </select>
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_production_break" value="{{ request('filter_production_break') }}" 
                   placeholder="Отрыв..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[70px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_city" value="{{ request('filter_city') }}" 
                   placeholder="Город..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_profession" value="{{ request('filter_profession') }}" 
                   placeholder="Профессия..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[70px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_reason" value="{{ request('filter_reason') }}" 
                   placeholder="Причина..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_resource" value="{{ request('filter_resource') }}" 
                   placeholder="Ресурс..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_learning_type" value="{{ request('filter_learning_type') }}" 
                   placeholder="Вид..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_event_type" value="{{ request('filter_event_type') }}" 
                   placeholder="Меропр..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_discipline" value="{{ request('filter_discipline') }}" 
                   placeholder="Дисциплина..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            <select name="filter_cost_profit" class="w-full px-1 py-1 text-xs border rounded"
                    onchange="this.form.submit()">
                <option value="">Себест/Приб</option>
                <option value="Себестоимость" {{ request('filter_cost_profit') == 'Себестоимость' ? 'selected' : '' }}>Себест.</option>
                <option value="Прибыль" {{ request('filter_cost_profit') == 'Прибыль' ? 'selected' : '' }}>Прибыль</option>
            </select>
        </th>
        <th class="px-1 py-2">
            <input type="text" name="filter_author" value="{{ request('filter_author') }}" 
                   placeholder="Автор..." 
                   class="w-full px-1 py-1 text-xs border rounded min-w-[60px]"
                   onchange="this.form.submit()">
        </th>
        <th class="px-1 py-2">
            @if(request()->anyFilled(['filter_id', 'filter_req_id', 'filter_status', 'filter_one_time', 'filter_start_from', 'filter_end_to', 'filter_issue_date', 'filter_course', 'filter_provider', 'filter_form', 'filter_employee_type', 'filter_production_break', 'filter_city', 'filter_profession', 'filter_reason', 'filter_resource', 'filter_learning_type', 'filter_event_type', 'filter_discipline', 'filter_cost_profit', 'filter_author']))
                <a href="{{ route('requests.index') }}" 
                   class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300 inline-block w-full text-center">
                    ✕
                </a>
            @endif
        </th>
    </tr>
    
    <!-- Строка с заголовками -->
    <tr>
        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase" style="width: 40px;">
            <input type="checkbox" id="select-all-requests" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        </th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">№ заявки</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Разов.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата нач.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата окон.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата созд.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Курс</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Провайдер</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Форма</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ИТР/раб.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Отрыв</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Город</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Профессия</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Причина</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ресурс</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Вид обуч.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Меропр.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дисциплина</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Себест./Приб.</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Автор</th>
        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
    </tr>
</thead>        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($requests as $request)
            <tr class="hover:bg-gray-50">
                <!-- Чекбокс строки -->
                <!-- Измененный чекбокс внутри <tbody> -->
                <td class="px-2 py-4 whitespace-nowrap text-sm">
                    @if(($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit') && $request->employees_count > 0)
                    <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" form="mass-send-form" class="request-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    @else
                    <input type="checkbox" disabled class="rounded border-gray-200 bg-gray-100 cursor-not-allowed" title="{{ ($request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit') ? 'Заявка не в статусе Создана' : 'В заявке нет сотрудников' }}">
                    @endif
                </td>

                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->id }}</td>
                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->req_id ?? '—' }}</td>
                <td class="px-1 py-4 whitespace-nowrap">
                    <span class="px-1 inline-flex text-xs leading-5 font-semibold rounded-full 
           @if($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit') bg-yellow-100 text-yellow-800
            @elseif($request->status === 'Отправлена' || $request->status === 'sent') bg-blue-100 text-blue-800
            @elseif($request->status === 'accepted') bg-green-100 text-green-800
            @elseif($request->status === 'rejected') bg-red-100 text-red-800
            @elseif($request->status === 'urpedit') bg-orange-100 text-orange-800
            @elseif($request->status === 'in_progress') bg-purple-100 text-purple-800
            @else bg-gray-100 text-gray-800
            @endif">
        {{ $request->status_label }}
                                    </span>
                                </td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->one_time ? 'Да' : 'Нет' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->start_date ? $request->start_date->format('d.m.Y') : '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->end_date ? $request->end_date->format('d.m.Y') : '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->issue_date ? $request->issue_date->format('d.m.Y') : '—' }}</td>
                                <td class="px-1 py-4 text-sm text-gray-900" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->course->course ?? '' }}">{{ Str::limit($request->course->course ?? '—', 40) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->provider->name ?? '' }}">{{ Str::limit($request->provider->name ?? '—', 40) }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->education_form ?? '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->employee_type ?? '—' }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->production_break ?? '' }}">{{ Str::limit($request->production_break ?? '—', 20) }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->city->city ?? '—' }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->profession->name ?? '' }}">{{ Str::limit($request->profession->name ?? '—', 30) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->learnReason->name ?? '' }}">{{ Str::limit($request->learnReason->name ?? '—', 30) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->learningResource->name ?? '' }}">{{ Str::limit($request->learningResource->name ?? '—', 30) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->learningType->name ?? '' }}">{{ Str::limit($request->learningType->name ?? '—', 30) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->eventType->name ?? '' }}">{{ Str::limit($request->eventType->name ?? '—', 30) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->discipline->name ?? '' }}">{{ Str::limit($request->discipline->name ?? '—', 30) }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->cost_profit ?? '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500" style="max-width: 100px; overflow: hidden; text-overflow: ellipsis;" title="{{ $request->user->name ?? '' }}">{{ $request->user->name ?? '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">

                                        <!-- Одиночная отправка -->
                                        @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) && ($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit') && $request->employees_count > 0)
                                        <button type="button"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-bold border border-blue-300 rounded px-1"
                                            title="Отправить одну заявку"
                                            data-id="{{ $request->id }}"
                                            onclick="sendSingleRequest(this)">📤
                                        </button>
                                        @endif

                                        <!-- Посмотреть -->
                                        <a href="{{ route('requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900" title="Посмотреть">👁️</a>

                                        <!-- Редактировать - только для urp, urp admin, admin если статус "Создана" -->
                                        @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) || ($request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit'))
                                        <a href="{{ route('requests.edit', $request) }}" class="text-indigo-600 hover:text-indigo-900" title="Редактировать">✏️</a>
                                        @endif

                                        <!-- Сотрудники - только для urp, urp admin, admin если статус "Создана" -->
                                        @if(auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) || ($request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit'))
                                        <a href="{{ route('request-employees.index', $request->id) }}" class="text-green-600 hover:text-green-900 relative" title="Сотрудники">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            @if($request->employees_count > 0)
                                            <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $request->employees_count }}</span>
                                            @endif
                                        </a>
                                        @endif

                                        <!-- Кнопка "Удалить" - только urp/urp admin если статус "Создана" или "На доработке" -->
                                        @if(auth()->user()->hasRole('admin') || 
                                            (auth()->user()->hasAnyRole(['urp', 'urp admin']) && ($request->status === 'Создана' || $request->status === 'created' || $request->status === 'urpedit')) ||
                                            (auth()->user()->hasAnyRole(['ooo', 'ooo admin', 'ooo chief']) && $request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit'))
                                        <form action="{{ route('requests.destroy', $request) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить" onclick="return confirm('Удалить?')">🗑️</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="25" class="px-1 py-4 text-center text-sm text-gray-500">Заявок пока нет</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                


                <!-- Пагинация страниц -->
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Логика работы UI на JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-requests');
            const rowCheckboxes = document.querySelectorAll('.request-checkbox');
            const massSendBtn = document.getElementById('btn-mass-send');

            // Показ кнопки массовой отправки при наличии выбранных элементов
            function toggleMassButton() {
                const checkedCount = document.querySelectorAll('.request-checkbox:checked').length;
                if (checkedCount > 0) {
                    massSendBtn.classList.remove('hidden');
                } else {
                    massSendBtn.classList.add('hidden');
                }
            }

            // Выбрать/снять все чекбоксы одной галочкой
            selectAllCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => {
                    if (!cb.disabled) {
                        cb.checked = selectAllCheckbox.checked;
                    }
                });
                toggleMassButton();
            });

            // Слушатель для каждой отдельной галочки в строке
            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    toggleMassButton();
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    }
                });
            });
        });

        // Функция обработки клика по иконке одиночной отправки в строке
        function sendSingleRequest(button) {
            // Достаем ID из data-атрибута нажатой кнопки
            const id = button.getAttribute('data-id');

            if (confirm('Отправить эту заявку в ООО?')) {
                const form = document.getElementById('mass-send-form');

                // Сброс остальных чекбоксов
                document.querySelectorAll('.request-checkbox').forEach(cb => cb.checked = false);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'request_ids[]';
                input.value = id;

                form.appendChild(input);
                form.submit();
            }
        }

    // Автопоиск с задержкой (debounce)
    let searchTimeout;
    const searchInput = document.getElementById('globalSearch');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
    
    // Автообновление при изменении select
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    // Автообновление при изменении даты
    document.querySelectorAll('#filterForm input[type="date"]').forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
</x-layouts.app-with-sidebar>
<style>
    /* Убираем стандартную стрелку у select */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none !important;
        padding-right: 0.5rem;
    }   
</style>