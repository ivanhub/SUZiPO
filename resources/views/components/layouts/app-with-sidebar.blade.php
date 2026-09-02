<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="app()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'СУЗиПО') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 shadow-sm flex flex-col" 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               x-show="sidebarOpen"
               @click.away="sidebarOpen = false">
            
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-gray-200">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-lg font-bold text-gray-800">СУЗиПО</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <!-- Главная -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                          {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Главная
                </a>

                <!-- Заявки -->
                <div x-data="{ open: true }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                                   {{ request()->routeIs('requests.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Заявки
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="open ? 'rotate-90' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-1 ml-8 space-y-1">
                        <a href="{{ route('requests.create') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Создать</a>
                        <a href="{{ route('requests.index', ['status' => 'draft']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Черновик</a>
                        <a href="{{ route('requests.index', ['status' => 'rejected']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Отвергнутые</a>
                        <a href="{{ route('requests.index', ['status' => 'sent']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Отправленные</a>
                        <a href="{{ route('requests.index', ['status' => 'accepted']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Принятые</a>
                        <a href="{{ route('requests.index', ['status' => 'trash']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Корзина</a>
                        <a href="{{ route('requests.index', ['status' => 'archive']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Архив</a>
                        <a href="{{ route('requests.export-form') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Экспорт заявок</a>
                    </div>
                </div>

                <!-- Протоколы -->
                <div x-data="{ open: true }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                                   {{ request()->routeIs('protocols.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Протоколы
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="open ? 'rotate-90' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-1 ml-8 space-y-1">
                        <a href="{{ route('protocols.index', ['status' => 'draft']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Черновик</a>
                        <a href="{{ route('protocols.index', ['status' => 'rejected']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Отвергнутые</a>
                        <a href="{{ route('protocols.index', ['status' => 'pending']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">На утверждении</a>
                        <a href="{{ route('protocols.index', ['status' => 'approved']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Принятые</a>
                        <a href="{{ route('protocols.index', ['status' => 'trash']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Корзина</a>
                        <a href="{{ route('protocols.index', ['status' => 'archive']) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Архив</a>
                    </div>
                </div>

                <!-- Бронирование аудиторий -->
                <a href="{{ route('bookings.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                          {{ request()->routeIs('bookings.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Бронирование аудиторий
                </a>

                <!-- Справочники -->
                <div x-data="{ open: true }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                                   {{ request()->routeIs('directories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Справочники
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="open ? 'rotate-90' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-1 ml-8 space-y-1 max-h-48 overflow-y-auto">
                        <a href="{{ route('directories.countries') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Страны</a>
                        <a href="{{ route('directories.reasons-non-certification') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Причины не аттестации</a>
			<a href="{{ route('directories.providers.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Учебные заведения</a>
                        <a href="{{ route('directories.courses.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Курсы</a>
                        <a href="{{ route('directories.cities.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Города</a>
                        <a href="{{ route('directories.professions.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Профессии</a>
                        <a href="{{ route('directories.qualifications') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Квалификация</a>
                        <a href="{{ route('directories.employees') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Работники</a>
                        <a href="{{ route('directories.departments') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Учебные отделы</a>
                        <a href="{{ route('directories.learning-types.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Виды обучения</a>
                        <a href="{{ route('directories.reasons-rejection') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Причины отказа</a>
                        <a href="{{ route('directories.categories') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Категории</a>
                        <a href="{{ route('directories.document-types') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Типы документов</a>
                        <a href="{{ route('directories.training-type') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Тип обучения</a>
                        <a href="{{ route('directories.course-authors') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Автор курса</a>
                        <a href="{{ route('directories.training-directions') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Направление обучения</a>
                        <a href="{{ route('directories.cost-allocation') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Отнесение затрат</a>
                        <a href="{{ route('directories.orders') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Приказы</a>
                        <a href="{{ route('directories.learn-reasons.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Причины обучения</a>
                        <a href="{{ route('directories.contracts') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Договора на обучение/оценку</a>
                        <a href="{{ route('directories.disciplines.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Дисциплины</a>
                        <a href="{{ route('directories.learning-types.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Вид обучения/оценки</a>
                        <a href="{{ route('directories.learning-resources.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Ресурс обучения/оценки</a>
                        <a href="{{ route('directories.events-type.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Виды мероприятий</a>
                        <a href="{{ route('directories.teachers.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Преподаватели УЦ</a>
                        <a href="{{ route('directories.audiences.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Аудитории УЦ</a>
                        <a href="{{ route('directories.curators.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Кураторы групп</a>
                        <a href="{{ route('directories.absence-types') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Виды отсутствий</a>
                    </div>
                </div>

                <!-- Отчёты -->
                <div x-data="{ open: true }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                                   {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Отчёты
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="open ? 'rotate-90' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-1 ml-8 space-y-1">
                        <a href="{{ route('reports.auditorium-load') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Аудиторная нагрузка</a>
                        <a href="{{ route('reports.training-journal') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Журнал обучения</a>
                        <a href="{{ route('reports.certificate-register') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Журнал регистрации удостоверений</a>
                        <a href="{{ route('reports.certificate-print') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Печать удостоверений</a>
                    </div>
                </div>

                <!-- Администрирование -->
                <div x-data="{ open: true }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                                   {{ request()->routeIs('admin.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Администрирование
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="open ? 'rotate-90' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-1 ml-8 space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Пользователи</a>
                        <a href="{{ route('admin.technical-task') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Техническое задание</a>
                        <a href="{{ route('admin.notifications') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Уведомления</a>
                        <a href="{{ route('admin.global-settings') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Глобальные настройки</a>
                    </div>
                </div>

                <!-- Ошибки -->
                <div x-data="{ open: true }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out
                                   {{ request()->routeIs('errors.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Ошибки
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="open ? 'rotate-90' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-1 ml-8 space-y-1">
                        <a href="{{ route('errors.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Посмотреть</a>
                        <a href="{{ route('errors.create') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Добавить</a>
                        <a href="{{ route('errors.non-certified') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Список не аттестованных</a>
                        <a href="{{ route('errors.analysis') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Анализ данных</a>
                        <a href="{{ route('errors.help-admin') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Справка - Администратор</a>
                        <a href="{{ route('errors.help-muo') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Справка - МУО</a>
                        <a href="{{ route('errors.help-srp') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Справка - СРП</a>
                        <a href="{{ route('errors.help-sumo') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-150">Справка - СУМО</a>
                    </div>
                </div>
            </nav>

            <!-- User info -->
            <div class="border-t border-gray-200 p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-medium">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <!-- Breadcrumbs or page title -->
                <h1 class="text-lg font-semibold text-gray-800" x-text="pageTitle">Dashboard</h1>
                <div class="ml-auto flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               placeholder="Быстрый поиск..."
                               class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <svg class="absolute left-3 top-2.5 text-gray-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <!-- Notifications -->
                    <button class="relative text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <p>© {{ date('Y') }} СУЗиПО. Все права защищены.</p>
                    <p>Версия 0.1</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function app() {
            return {
                sidebarOpen: true,
                pageTitle: 'Главная',
                init() {
                    const path = window.location.pathname;
                    if (path === '/dashboard' || path === '/') {
                        this.pageTitle = 'Главная';
                    } else if (path.includes('/requests')) {
                        this.pageTitle = 'Заявки';
                    } else if (path.includes('/protocols')) {
                        this.pageTitle = 'Протоколы';
                    } else if (path.includes('/bookings')) {
                        this.pageTitle = 'Бронирование аудиторий';
                    } else if (path.includes('/directories')) {
                        this.pageTitle = 'Справочники';
                    } else if (path.includes('/reports')) {
                        this.pageTitle = 'Отчёты';
                    } else if (path.includes('/admin')) {
                        this.pageTitle = 'Администрирование';
                    } else if (path.includes('/errors')) {
                        this.pageTitle = 'Ошибки';
                    }
                }
            }
        }
    </script>

    @stack('scripts')
</body>
</html>