<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="suzipoLayout()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'СУЗиПО') }}</title>

    <!-- Мгновенная инициализация темы до отрисовки страницы (без мерцания) -->
    <script>
        if (localStorage.getItem('suzipo_theme') === 'dark' || (!('suzipo_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mermaid.min.css') }}" rel="stylesheet">

    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Верхняя адаптивная шапка (Top Header) -->
    <header class="sticky top-0 z-40 h-16 px-6 flex items-center justify-between border-b transition-colors"
            style="background-color: var(--bg-page); border-color: var(--border-color);">

        <!-- Левая часть: Логотип РОСНЕФТЬ | СУЗиПО -->
                <!-- Левая часть: Логотип -->
                <!-- Левая часть: Адаптивный логотип под светлую и тёмную тему -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard') }}" class="flex items-center group">
                <img x-show="!darkMode" src="{{ asset('images/logo-light.svg') }}" alt="РОСНЕФТЬ СУЗиПО" style="height: 42px; width: auto;">
                <img x-show="darkMode" x-cloak src="{{ asset('images/logo-dark.svg') }}" alt="РОСНЕФТЬ СУЗиПО" style="height: 42px; width: auto;">
            </a>
        </div>
        <!-- Центральная капсула навигации (показывается только на внутренних страницах) -->
        @if(!request()->routeIs('dashboard') && request()->path() !== '/')
        <nav class="hidden lg:flex items-center px-2 py-1.5 rounded-full border space-x-1"
             style="background-color: var(--bg-surface); border-color: var(--border-color);">

            <a href="{{ route('dashboard') }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors"
               style="color: var(--text-secondary);">
                Главная
            </a>

            <!-- Заявки -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="{{ route('requests.index') }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors inline-flex items-center gap-1"
                   style="{{ request()->routeIs('requests.*') ? 'background-color: var(--brand-yellow); color: #111827; font-weight: 600;' : 'color: var(--text-primary);' }}">
                    Заявки
                </a>
                <div x-show="open" x-cloak x-transition
                     class="absolute left-0 mt-1 w-48 rounded-xl shadow-lg border py-1.5 z-50"
                     style="background-color: var(--bg-surface); border-color: var(--border-color);">
                    <a href="{{ route('requests.index') }}" class="block px-4 py-1.5 text-xs hover:opacity-80" style="color: var(--text-primary);">Все заявки</a>
                    @urp
                    <a href="{{ route('requests.create') }}" class="block px-4 py-1.5 text-xs font-semibold" style="color: #D97706;">+ Создать заявку</a>
                    @endurp
                    <div class="my-1 border-t" style="border-color: var(--border-color);"></div>
                    <a href="{{ route('requests.index', ['status' => 'created']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Создана</a>
                    <a href="{{ route('requests.index', ['status' => 'sent']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Отправлена</a>
                    <a href="{{ route('requests.index', ['status' => 'in_progress']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">В работе</a>
                    <a href="{{ route('requests.index', ['status' => 'urpedit']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">На доработке</a>
                    <a href="{{ route('requests.index', ['status' => 'accepted']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Принята</a>
                    <a href="{{ route('requests.index', ['status' => 'rejected']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Отклонена</a>
                    <div class="my-1 border-t" style="border-color: var(--border-color);"></div>
                    <a href="{{ route('requests.export-form') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Экспорт заявок</a>
                </div>
            </div>

            <!-- Протоколы -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="{{ route('protocols.index') }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors inline-flex items-center gap-1"
                   style="{{ request()->routeIs('protocols.*') ? 'background-color: var(--brand-yellow); color: #111827; font-weight: 600;' : 'color: var(--text-primary);' }}">
                    Протоколы
                </a>
                <div x-show="open" x-cloak x-transition
                     class="absolute left-0 mt-1 w-44 rounded-xl shadow-lg border py-1.5 z-50"
                     style="background-color: var(--bg-surface); border-color: var(--border-color);">
                    <a href="{{ route('protocols.index', ['status' => 'draft']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Черновик</a>
                    <a href="{{ route('protocols.index', ['status' => 'rejected']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Отвергнутые</a>
                    <a href="{{ route('protocols.index', ['status' => 'pending']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">На утверждении</a>
                    <a href="{{ route('protocols.index', ['status' => 'approved']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Принятые</a>
                    <a href="{{ route('protocols.index', ['status' => 'trash']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Корзина</a>
                    <a href="{{ route('protocols.index', ['status' => 'archive']) }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Архив</a>
                </div>
            </div>

            <!-- Матрицы / Программы -->
            <a href="{{ route('matrices.index') }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors"
               style="{{ request()->routeIs('matrices.*') ? 'background-color: var(--brand-yellow); color: #111827; font-weight: 600;' : 'color: var(--text-primary);' }}">
                Программы
            </a>

            <!-- Справочники -->
            <a href="{{ route('directories.courses.index') }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors"
               style="{{ request()->routeIs('directories.*') ? 'background-color: var(--brand-yellow); color: #111827; font-weight: 600;' : 'color: var(--text-primary);' }}">
                Справочники
            </a>
            <!-- Отчёты (дропдаун) -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="{{ route('reports.training-journal') }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors"
                   style="{{ request()->routeIs('reports.*') ? 'background-color: var(--brand-yellow); color: #111827; font-weight: 600;' : 'color: var(--text-primary);' }}">
                    Отчёты
                </a>
                <div x-show="open" x-cloak x-transition
                     class="absolute left-0 mt-1 w-56 rounded-xl shadow-lg border py-1.5 z-50"
                     style="background-color: var(--bg-surface); border-color: var(--border-color);">
                    <a href="{{ route('reports.auditorium-load') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Аудиторная нагрузка</a>
                    <a href="{{ route('reports.training-journal') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Журнал обучения</a>
                    <a href="{{ route('reports.certificate-register') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Журнал регистрации удостоверений</a>
                    <a href="{{ route('reports.certificate-print') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Печать удостоверений</a>
                </div>
            </div>

            <!-- Ещё (Бронирование, Админка, Ошибки) -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button type="button"
                        class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors inline-flex items-center gap-1"
                        style="color: var(--text-secondary);">
                    Ещё
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak x-transition
                     class="absolute right-0 mt-1 w-52 rounded-xl shadow-lg border py-1.5 z-50"
                     style="background-color: var(--bg-surface); border-color: var(--border-color);">
                    <a href="{{ route('bookings.index') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Бронирование аудиторий</a>
                    <div class="my-1 border-t" style="border-color: var(--border-color);"></div>
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Пользователи</a>
                    <a href="{{ route('admin.global-settings') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-primary);">Настройки</a>
                    <div class="my-1 border-t" style="border-color: var(--border-color);"></div>
                    <a href="{{ route('errors.index') }}" class="block px-4 py-1.5 text-xs" style="color: var(--text-secondary);">Журнал ошибок</a>
                </div>
            </div>
        </nav>
        @endif

                <!-- Правая часть: Поиск, Уведомления, Переключатель темы и Профиль (без зависимости от npm run build) -->
        <div style="display: flex; align-items: center; gap: 20px;">
            <!-- 1. Иконка поиска -->
            <button type="button" title="Поиск"
                    style="background: transparent; border: none; padding: 4px; color: var(--text-primary); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
            </button>

            <!-- 2. Иконка уведомлений -->
            <button type="button" title="Уведомления"
                    style="background: transparent; border: none; padding: 4px; color: var(--text-primary); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>

            <!-- 3. Переключатель темы (Солнце / Луна) -->
            <button type="button" @click="toggleTheme()" title="Переключить тему"
                    style="background: transparent; border: none; padding: 4px; color: var(--text-primary); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                <!-- Иконка Солнца (для светлой темы) -->
                <svg x-show="!darkMode" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="4"/>
                    <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32l1.41-1.41"/>
                </svg>
                <!-- Иконка Луны (для тёмной темы) -->
                <svg x-show="darkMode" x-cloak width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </button>

            <!-- 4. Профиль текущего пользователя -->
            @if(auth()->user())
            @php
                $user = auth()->user();
                $parts = preg_split('/\s+/u', trim((string)$user->name)) ?: [];
                $firstChar = mb_substr($parts[0] ?? '', 0, 1);
                $secondChar = isset($parts[1]) ? mb_substr($parts[1], 0, 1) : mb_substr($parts[0] ?? '', 1, 1);
                $initials = mb_strtoupper($firstChar . $secondChar);

                // Определяем статус/роль пользователя (если есть роль, иначе email)
                $roleMap = [
                    'admin'     => 'Администратор',
                    'urp'       => 'Куратор УРП',
                    'uc'        => 'Сотрудник УЦ',
                    'methodist' => 'Методист',
                ];
                $rawRole = $user->role ?? $user->status ?? null;
                $userSubtext = $rawRole ? ($roleMap[mb_strtolower((string)$rawRole)] ?? $rawRole) : $user->email;
            @endphp
            <div style="position: relative;" x-data="{ userOpen: false }">
                <button type="button" @click="userOpen = !userOpen" @click.away="userOpen = false"
                        style="display: flex; align-items: center; gap: 12px; background: transparent; border: none; padding: 0; text-align: left; cursor: pointer;">
                    <!-- Круглый серый аватар с инициалами -->
                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--bg-surface); color: var(--text-primary); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0;">
                        {{ $initials }}
                    </div>
                    <!-- ФИО и статус/email в две строки -->
                    <div style="line-height: 1.25;">
                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap;">
                            {{ $user->name }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-secondary); margin-top: 2px; white-space: nowrap;">
                            {{ $userSubtext }}
                        </div>
                    </div>
                </button>

                <!-- Выпадающее меню (строго position: absolute) -->
                <div x-show="userOpen" x-cloak
                     style="position: absolute; right: 0; top: calc(100% + 10px); width: 210px; border-radius: 12px; background-color: var(--bg-surface); border: 1px solid var(--border-color); box-shadow: 0 10px 25px rgba(0,0,0,0.12); padding: 6px 0; z-index: 999;">
                    <div style="padding: 8px 14px; border-bottom: 1px solid var(--border-color); font-size: 11px; color: var(--text-secondary);">
                        {{ $user->email }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit"
                                style="width: 100%; text-align: left; padding: 8px 14px; font-size: 12px; color: #EF4444; background: transparent; border: none; cursor: pointer;">
                            Выйти из системы
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </header>
    <!-- Основной контейнер контента -->
    <main class="flex-1 w-full max-w-[1440px] mx-auto px-6 py-6">
        {{ $slot }}
    </main>

    <!-- Лаконичный подвал -->
    <footer class="border-t py-4 px-6 mt-auto transition-colors"
            style="border-color: var(--border-color); color: var(--text-secondary);">
        <div class="max-w-[1440px] mx-auto flex items-center justify-between text-xs">
            <p>© {{ date('Y') }} ООО «СИБИНТЕК-СОФТ» — СУЗиПО. Все права защищены.</p>
            <p>Версия 0.1</p>
        </div>
    </footer>

    <script>
        function suzipoLayout() {
            return {
                darkMode: document.documentElement.classList.contains('dark'),
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('suzipo_theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('suzipo_theme', 'light');
                    }
                    console.info('Тема интерфейса переключена на:', this.darkMode ? 'Темную (Dark)' : 'Светлую (Light)');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const numberInputs = document.querySelectorAll('input[type="number"]');

            numberInputs.forEach(function (input) {
                const isDecimal = input.step && input.step !== '1';
                input.setAttribute('inputmode', isDecimal ? 'decimal' : 'numeric');

                input.addEventListener('keydown', function (e) {
                    const forbidden = ['e', 'E', '+', '-'];
                    if (!isDecimal) {
                        forbidden.push('.', ',');
                    }

                    if (forbidden.includes(e.key)) {
                        e.preventDefault();
                        console.warn(`Заблокирован ввод недопустимого символа "${e.key}" в поле [${input.name || input.id}]`);
                    }
                });

                input.addEventListener('input', function () {
                    let val = this.value;
                    if (isDecimal && val.includes(',')) {
                        val = val.replace(',', '.');
                        this.value = val;
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
