<x-layouts.app-with-sidebar>
    <div class="max-w-full mx-auto p-6" style="font-family: system-ui, -apple-system, sans-serif; background-color: #fafafa; min-height: 100vh;"
        x-data="{ activeTab: 'report' }">

        <!-- Верхняя -->
        <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <div>
                <a href="{{ route('protocols.index') }}" style="text-decoration: none; color: #4f46e5; font-size: 13px; font-weight: 500;">&larr; Вернуться в реестр</a>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 4px 0 0 0;">Создание отчета по протоколу № {{ $protocol->prot_num }}</h1>
                <p style="font-size: 13px; color: #6b7280; margin: 2px 0 0 0;">Вид обучения: <span class="font-semibold text-gray-800">{{ $learningTypeName }}</span></p>
            </div>
        </div>

        <!-- Переключатели вкладок (Табы) в стиле сайта -->
        <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 24px; font-size: 14px; border-bottom: 1px solid #e5e7eb; padding-bottom: 0; overflow-x: auto; white-space: nowrap;">
            <!-- Вкладка 1: Отчет (Универсальная) -->
            <span @click="activeTab = 'report'"
                :style="activeTab === 'report' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: -1px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 12px;'">
                📋 Отчет
            </span>

            <!-- Вкладка 2: Список (Универсальная) -->
            <span @click="activeTab = 'list'"
                :style="activeTab === 'list' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: -1px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 12px;'">
                👥 Список обучаемых
            </span>

            <!-- Вкладка 3: Протокол (Динамическое название!) -->
            <span @click="activeTab = 'protocol'"
                :style="activeTab === 'protocol' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: -1px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 12px;'">
                📜 Протокол {{ $learningTypeName }}
            </span>
        </div>

        <!-- ОТЧЕТ -->
        <form action="{{ route('protocols.report.store') }}" method="POST">
            @csrf

            <!-- СОДЕРЖИМОЕ ВКЛАДКИ 1: ОТЧЕТ -->
            <div x-show="activeTab === 'report'" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

                <!-- ИНФОРМАЦИОННЫЙ БЛОК (Поля из заявки, которые скрыты из формы ввода, но нужны для просмотра) -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; padding: 12px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 12px; color: #4b5563;">
                    <div><b>Вид обучения:</b> <span class="text-gray-900 font-semibold">{{ $protocol->demand?->learningType?->name ?? '—' }}</span></div>
                    <div><b>Форма обучения:</b> <span class="text-gray-900 font-semibold">{{ $protocol->demand?->education_form ?? 'дистанционное' }}</span></div>
                    <div><b>Программа:</b> <span class="text-gray-900 font-semibold">«{{ $protocol->demand?->course?->course ?? '—' }}»</span></div>
                    <div><b>Период обучения (план):</b> <span class="text-gray-900 font-semibold">с {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }} по {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}</span></div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 12px;">

                    <!-- Ряд 1: Ф.И.О. специалиста ООО -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Ф.И.О. специалиста:</label>
                        <select name="user_oo_id" style="width: 100%; max-width:385px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 34px;">
                            <option value="{{ auth()->id() }}">{{ auth()->user()->name ?? 'Заковырин Т.Ю.' }}</option>
                        </select>
                    </div>

                    <!-- Ряд 2: Распоряжение № / Приказ № -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Распоряжение №/ Приказ №:</label>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            <input type="text" name="order_num" value="{{ $protocol->order_num }}" style="width: 100%; max-width: 180px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px;">
                            <span style="padding: 5px 10px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; font-weight: 600; font-size: 11px; height: 34px; display: flex; align-items: center;">от</span>
                            <input type="date" name="order_date" value="{{ $protocol->order_date }}" style="width: 160px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px;">
                        </div>
                    </div>

                    <!-- Ряд 3: Протокол № + ЕИСОТ Чекбоксы + ЦТКК -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: start; gap: 12px;">
                        <label style="font-weight: 500; color: #374151; margin-top: 8px;">Протокол №:</label>
                        <div style="display: flex; flex-direction: column; gap: 8px; width: 100%;">
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <input type="text" name="prot_num" value="{{ $protocol->prot_num }}" readonly style="width: 100%; max-width: 180px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #f9fafb; font-bold; height: 34px; cursor: not-allowed;">
                                <span style="padding: 5px 10px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; font-weight: 600; font-size: 11px; height: 34px; display: flex; align-items: center;">от</span>
                                <input type="date" name="prot_date" value="{{ $protocol->date_end }}" style="width: 160px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px;">
                                <label style="display: inline-flex; align-items: center; gap: 4px; margin-left: 12px; font-weight: 600;"><input type="checkbox" name="is_ctkk" value="1"> ЦТКК</label>
                            </div>
                            <!-- Линейка чекбоксов ЕИСОТ со скрина формы -->
                            <div style="display: flex; gap: 16px; font-size: 11px; color: #4b5563; flex-wrap: wrap; margin-top: 2px;">
                                <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" name="not_required_eisot" value="1"> не требуется проверка знаний в ЕИСОТ</label>
                                <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" name="is_main_eisot" value="1"> Основной протокол (ЕИСОТ)</label>
                                <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" name="fact_eisot" value="1"> Факт прохождения ЕИСОТ</label>
                            </div>
                        </div>
                    </div>

                    <!-- Ряд 4: Группа № и Аудитория № -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Группа № / Аудитория №:</label>
                        <div style="display: flex; gap: 16px; width: 100%;">
                            <input type="text" name="group_num" value="{{ $protocol->prot_num }}" readonly style="width: 100%; max-width: 180px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #f9fafb; font-bold; height: 34px; cursor: not-allowed;">
                            <select name="audience_id" style="width: 100%; max-width: 220px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 34px;">
                                <option value="">— Выберите аудиторию —</option>
                                @foreach($audiences as $audience)
                                <option value="{{ $audience->id }}" {{ $protocol->demand?->audience_id == $audience->id ? 'selected' : '' }}>Аудитория №{{ $audience->number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ряд 5: Фактическая дата начала (для титульного) -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Фактическая Дата начала обучения (для титульного):</label>
                        <input type="date" name="start_date_fact" value="{{ $protocol->date_start }}" style="width: 160px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px;">
                    </div>

                    <!-- Блок Часов по программе (Ультракомпактные числовые селекты h-[34px]) -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;">
                        <label style="font-weight: 600; color: #111827; margin-top: 6px;">Распределение учебных часов:</label>
                        <div style="display: flex; flex-direction: column; gap: 8px; max-width: 320px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Кол-во часов по программе:</span> <input type="number" name="program_hours" value="{{ $protocol->program_hours ?? 0 }}" style="width: 80px; padding: 4px; border: 1px solid #d1d5db; border-radius: 6px; text-align: center; height: 30px;"></div>
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Кол-во часов теоретического обучения:</span> <input type="number" name="theory_hours" value="{{ $protocol->theory_hours ?? 0 }}" style="width: 80px; padding: 4px; border: 1px solid #d1d5db; border-radius: 6px; text-align: center; height: 30px;"></div>
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Кол-во часов производственно-практ.:</span> <input type="number" name="prod_hours" value="0" style="width: 80px; padding: 4px; border: 1px solid #d1d5db; border-radius: 6px; text-align: center; height: 30px;"></div>
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Кол-во часов практического обучения:</span> <input type="number" name="practice_hours" value="{{ $protocol->practice_hours ?? 0 }}" style="width: 80px; padding: 4px; border: 1px solid #d1d5db; border-radius: 6px; text-align: center; height: 30px;"></div>
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Кол-во часов самостоятельной подг.:</span> <input type="number" name="self_hours" value="0" style="width: 80px; padding: 4px; border: 1px solid #d1d5db; border-radius: 6px; text-align: center; height: 30px;"></div>
                        </div>
                    </div>

                    <!-- Блок: Категория обучаемых (Авторасчет по сотрудникам группы) -->
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;">
                        <span style="display: block; font-weight: 700; color: #4b5563; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; margin-bottom: 10px; text-align: center; background: #f3f4f6; padding: 4px; border-radius: 4px;">Категория обучаемых</span>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="display:block; color:#6b7280; font-size:11px; margin-bottom:2px;">Рабочие</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('position_type', 'Рабочий')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; font-weight:600; text-align:center;">
                            </div>
                            <div>
                                <label style="display:block; color:#6b7280; font-size:11px; margin-bottom:2px;">Руководители</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('position_type', 'Руководитель')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; font-weight:600; text-align:center;">
                            </div>
                            <div>
                                <label style="display:block; color:#6b7280; font-size:11px; margin-bottom:2px;">Специалисты</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('position_type', 'Специалист')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; font-weight:600; text-align:center;">
                            </div>
                        </div>
                    </div>

                    <!-- Блок: Группы предприятий (Авторасчет по компаниям сотрудников) -->
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;">
                        <span style="display: block; font-weight: 700; color: #4b5563; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; margin-bottom: 10px; text-align: center; background: #f3f4f6; padding: 4px; border-radius: 4px;">Группы предприятий</span>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                            <div>
                                <label style="display:block; color:#6b7280; font-size:10px; margin-bottom:2px; text-align:center;">ООО "РН-ЮНГ"</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('company_type', 'РН-ЮНГ')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; text-align:center; font-weight:600;">
                            </div>
                            <div>
                                <label style="display:block; color:#6b7280; font-size:10px; margin-bottom:2px; text-align:center;">СЕРВИС</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('company_type', 'Сервис')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; text-align:center; font-weight:600;">
                            </div>
                            <div>
                                <label style="display:block; color:#6b7280; font-size:10px; margin-bottom:2px; text-align:center;">Прочие предприятия</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('company_type', 'Прочие')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; text-align:center; font-weight:600;">
                            </div>
                            <div>
                                <label style="display:block; color:#6b7280; font-size:10px; margin-bottom:2px; text-align:center;">Наличный расчет</label>
                                <input type="number" readonly value="{{ collect($protocol->demand?->employees)->where('company_type', 'Наличные')->count() }}" style="width:100%; padding:5px; border:1px solid #d1d5db; border-radius:6px; background:#f9fafb; text-align:center; font-weight:600;">
                            </div>
                        </div>
                    </div>

                    <!-- Ряд: Множественный выбор Преподавателей -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;">
                        <label style="font-weight: 500; color: #374151;">Преподаватель:</label>
                        <select name="teachers[]" symmetric-multiple="true" multiple style="width: 100%; max-width: 320px; padding: 6px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 68px;">
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ $protocol->demand?->teacher_id == $t->id ? 'selected' : '' }}>{{ $t->fio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ряд: Выбор куратора УРП -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Куратор УРП:</label>
                        <select name="curator_id" style="width: 100%; max-width: 320px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 34px;">
                            <option value="{{ $protocol->demand?->curator_id }}">{{ $protocol->demand?->curator?->fio ?? '—' }}</option>
                        </select>
                    </div>

                </div>
            </div> {{-- Закрывающий тег вкладки Отчет --}}




            <!-- СОДЕРЖИМОЕ ВКЛАДКИ 2: СПИСОК (Универсальная таблица сотрудников) -->
            <div x-show="activeTab === 'list'" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" x-cloak>
                <div style="margin-bottom: 16px; display: flex; align-items: center;">
                    <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: #111827;">Реестр обучаемых сотрудников (согласно заявке)</h3>
                    <span style="margin-left: auto; font-size: 12px; color: #6b7280;">Всего в группе: <b class="text-gray-900">{{ count($protocol->demand?->employees ?? []) }} чел.</b></span>
                </div>

                <div style="overflow-x: auto; width: 100%; border: 1px solid #e5e7eb; border-radius: 6px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left; color: #374151;">
                        <thead>
                            <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-weight: 600;">
                                <th style="padding: 10px 12px; width: 50px; text-align: center;">№</th>
                                <th style="padding: 10px 12px; width: 100px;">Таб. номер</th>
                                <th style="padding: 10px 12px; width: 220px;">Фамилия Имя Отчество</th>
                                <th style="padding: 10px 12px; width: 200px;">Должность</th>
                                <th style="padding: 10px 12px;">Подразделение (цех/участок)</th>
                                <th style="padding: 10px 12px; width: 180px;">Предприятие</th>
                                <th style="padding: 10px 12px; width: 80px; text-align: center;">Пол</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($protocol->demand?->employees ?? [] as $index => $employee)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 12px; text-align: center; color: #6b7280;">{{ $index + 1 }}</td>
                                <td style="padding: 10px 12px; font-weight: 500;">{{ $employee->tab_num ?? '—' }}</td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #111827;">{{ $employee->last_name ?? '—'}} {{ $employee->first_name ?? '—' }} {{ $employee->middle_name ?? '—'}}</td>
                                <td style="padding: 10px 12px; color: #4b5563;">{{ $employee->position ?? '—' }}</td>
                                <td style="padding: 10px 12px; color: #4b5563;">{{ $employee->department ?? '—' }}</td>
                                <td style="padding: 10px 12px; color: #4b5563;">{{ $employee->company ?? '—' }}</td>
                                <td style="padding: 10px 12px; text-align: center;"><span style="padding: 2px 6px; background: #f3f4f6; border-radius: 4px; font-size: 11px; font-weight: 600;">{{ $employee->gender ?? '—' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="padding: 24px; text-align: center; color: #9ca3af;">В данной заявке список сотрудников пуст</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- СОДЕРЖИМОЕ ВКЛАДКИ 3: ПРОТОКОЛ-->
            <div x-show="activeTab === 'protocol'" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" x-cloak>

                <!-- Шапка официального бланка из Excel -->
                <div style="text-align: center; font-family: 'Arial', sans-serif; color: #111827; margin-bottom: 24px; line-height: 1.4;">
                    <div style="font-weight: 800; font-size: 14px; letter-spacing: 0.03em;">ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ</div>
                    <div style="font-weight: 900; font-size: 16px;">«РН-ЮГАНСКНЕФТЕГАЗ»</div>
                    <div style="font-size: 12px; font-weight: 600; color: #374151;">(ООО «РН-ЮГАНСКНЕФТЕГАЗ»)</div>
                    <div style="font-weight: 800; font-size: 14px; margin-top: 4px; text-decoration: underline;">УЧЕБНЫЙ ЦЕНТР</div>

                    <h2 style="font-size: 16px; font-weight: 800; margin: 20px 0 6px 0; text-transform: uppercase;">ПРОТОКОЛ № {{ $protocol->prot_num }}</h2>
                    <div style="font-size: 12px; font-weight: 600; color: #4b5563;">заседания комиссии по проверке знаний требований охраны труда, назначенной</div>
                    <div style="font-size: 12px; margin-top: 2px;">распоряжением № <span style="font-weight: 700;">{{ $protocol->order_num ?? '_____' }}</span> от <span style="font-weight: 700;">{{ $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->format('d.m.Y') : '«___» ________ 202_ г.' }}</span></div>
                </div>

                <!-- Блок параметров программы обучения -->
                <div style="display: flex; flex-direction: column; gap: 6px; font-size: 12px; background: #fafafa; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb; margin-bottom: 20px;">
                    <div><b>По проверке знаний обучающихся группы №:</b> <span style="color: #4f46e5; font-weight: 700;">{{ $protocol->prot_num }}</span></div>
                    <div><b>Программа обучения:</b> <span style="font-weight: 600;">«{{ $protocol->demand?->course?->course ?? '—' }}»</span></div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-top: 4px; border-top: 1px solid #e5e7eb; padding-top: 6px;">
                        <div><b>Часов по программе:</b> {{ $protocol->hoursbyprogram ?? 0 }} ч.</div>
                        <div><b>Теория:</b> {{ $protocol->theoryhours ?? 0 }} ч.</div>
                        <div><b>Практика:</b> {{ $protocol->practicehours ?? 0 }} ч.</div>
                    </div>
                </div>

                <!-- Интерактивная таблица выставления оценок ОТ -->
                <div style="overflow-x: auto; width: 100%; border: 1px solid #a1a1a1;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: left; color: #111827;">
                        <thead>
                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #a1a1a1; text-align: center; font-weight: 700; height: 40px;">
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 35px;">№ пп</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 75px;">№ табельный</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 180px;">Фамилия Имя Отчество</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 160px;">Должность</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 180px;">Наименование подразделения</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 140px;">Предприятие</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 120px; color: #b91c1c;">Результат проверки<br>(сдал/не сдал)</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 110px;">Рег. номер ЕИСОТ</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 85px;">Начало (факт)</th>
                                <th style="border-right: 1px solid #a1a1a1; padding: 4px; width: 85px;">Окончание (факт)</th>
                                <th style="padding: 4px; width: 70px;">Часы (факт)</th>
                            </tr>
                            <tr style="background-color: #e5e7eb; border-bottom: 1px solid #a1a1a1; text-align: center; font-weight: 600; font-size: 10px; height: 18px; color: #4b5563;">
                                <td style="border-right: 1px solid #a1a1a1;">1</td>
                                <td style="border-right: 1px solid #a1a1a1;">2</td>
                                <td style="border-right: 1px solid #a1a1a1;">3</td>
                                <td style="border-right: 1px solid #a1a1a1;">4</td>
                                <td style="border-right: 1px solid #a1a1a1;">5</td>
                                <td style="border-right: 1px solid #a1a1a1;">6</td>
                                <td style="border-right: 1px solid #a1a1a1;">7</td>
                                <td style="border-right: 1px solid #a1a1a1;">8</td>
                                <td style="border-right: 1px solid #a1a1a1;">10</td>
                                <td style="border-right: 1px solid #a1a1a1;">11</td>
                                <td>12</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($protocol->demand?->employees ?? [] as $index => $employee)
                            <tr style="border-bottom: 1px solid #a1a1a1; height: 36px;">
                                <td style="border-right: 1px solid #a1a1a1; padding: 6px; text-align: center; color: #6b7280;">{{ $index + 1 }}</td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 6px; text-align: center;">{{ $employee->tab_num ?? '—' }}</td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 6px; font-weight: 700;">{{ $employee->last_name ?? '—'}} {{ $employee->first_name ?? '—' }} {{ $employee->middle_name ?? '—'}}</td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 6px; font-size: 10px; line-height: 1.2;">{{ $employee->position ?? '—' }}</td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 6px; font-size: 10px;">{{ $employee->department ?? '—' }}</td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 6px; font-size: 10px;">{{ $employee->company ?? '—' }}</td>

                                <!-- Поля ввода ИНПУТОВ оценок для сохранения в БД (Специалист ООО заполняет прямо тут!) -->
                                <td style="border-right: 1px solid #a1a1a1; padding: 4px; text-align: center;">
                                    <select name="results[{{ $employee->id }}][status]" style="width: 100%; padding: 2px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px;">
                                        <option value="удовлетворительно">удовлетворительно</option>
                                        <option value="неудовлетворительно">неудовлетворительно</option>
                                        <option value="неявка">неявка</option>
                                    </select>
                                </td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 4px;">
                                    <input type="text" name="results[{{ $employee->id }}][eisot_num]" placeholder="211509345" style="width: 100%; padding: 3px 6px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;">
                                </td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 4px; text-align: center;">
                                    <input type="date" name="results[{{ $employee->id }}][start_fact]" value="{{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('Y-m-d') : '' }}" style="width: 100%; padding: 2px; font-size: 10px; border: 1px solid #d1d5db; border-radius: 4px;">
                                </td>
                                <td style="border-right: 1px solid #a1a1a1; padding: 4px; text-align: center;">
                                    <input type="date" name="results[{{ $employee->id }}][end_fact]" value="{{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('Y-m-d') : '' }}" style="width: 100%; padding: 2px; font-size: 10px; border: 1px solid #d1d5db; border-radius: 4px;">
                                </td>
                                <td style="padding: 4px; text-align: center;">
                                    <input type="number" name="results[{{ $employee->id }}][hours_fact]" value="{{ $protocol->hoursbyprogram ?? 0 }}" style="width: 55px; padding: 3px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center;">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" style="padding: 24px; text-align: center; color: #9ca3af;">Список сотрудников для формирования протокола ОТ отсутствует</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin-top: 32px; padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
                <input type="hidden" name="id_protocol" value="{{ $protocol->prot_id }}">
                <a href="{{ route('protocols.index') }}" style="padding: 8px 20px; border: 1px solid #d1d5db; border-radius: 6px; background: #ffffff; font-weight: 500; text-decoration: none; color: #374151; font-size: 13px; display: inline-flex; align-items: center; h-[36px]" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='#ffffff'">Отмена</a>
                <button type="submit" style="padding: 8px 24px; border: none; border-radius: 6px; background: #10b981; color: #ffffff; font-weight: 600; font-size: 13px; cursor: pointer; h-[36px]; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'">💾 Сохранить отчет</button>
            </div>

        </form>

    </div>
</x-layouts.app-with-sidebar>