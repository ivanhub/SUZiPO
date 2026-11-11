<x-layouts.app-with-sidebar>
    <div class="max-w-full mx-auto p-6" style="font-family: system-ui, -apple-system, sans-serif; background-color: #fafafa; min-height: 100vh;"
        x-data="{ 
            activeTab: 'report',
            
            chairman: '{{ trim($currentComission->where('note', 'Председатель')->first()?->fio ?? '-') }}',
            deputy: '{{ trim($currentComission->where('note', 'Заместитель председателя')->first()?->fio ?? '-') }}',

            selectedMembers: [
                @foreach($currentComission->where('note', 'Член комиссии') as $cm)
                    @if(!empty(trim($cm->fio)) && trim($cm->fio) !== '-')
                        '{{ trim($cm->fio) }}',
                    @endif
                @endforeach
            ],
            

            toggleMember(fio) {
                if (this.selectedMembers.includes(fio)) {
                    this.selectedMembers = this.selectedMembers.filter(m => m !== fio);
                } else {
                    this.selectedMembers.push(fio);
                }
            }
         }">


        <!-- Верхняя -->
        <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <div>
                <a href="{{ route('protocols.index') }}" style="text-decoration: none; color: #4f46e5; font-size: 13px; font-weight: 500;">&larr; Вернуться в реестр</a>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 4px 0 0 0;">Создание отчета по протоколу № {{ $protocol->prot_num }}</h1>
                <p style="font-size: 13px; color: #6b7280; margin: 2px 0 0 0;">Вид обучения: <span class="font-semibold text-gray-800">{{ $learningTypeName }}</span></p>
            </div>
        </div>
        <input type="hidden" name="protocol_id" value="{{ $protocol->prot_id ?? $protocol->id ?? '' }}">

        <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 24px; font-size: 14px; border-bottom: 1px solid #e5e7eb; padding-bottom: 0; overflow-x: auto; white-space: nowrap;">
            <!-- Вкладка 1: Отчет -->
            <span @click="activeTab = 'report'"
                :style="activeTab === 'report' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: -1px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 12px;'">
                📋 Отчет
            </span>

            <!-- Вкладка 2: Список -->
            <span @click="activeTab = 'list'"
                :style="activeTab === 'list' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: -1px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 12px;'">
                👥 Список обучаемых
            </span>

            <!-- Вкладка 3: Протокол -->
            <span @click="activeTab = 'protocol'"
                :style="activeTab === 'protocol' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: -1px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 12px;'">
                📜 Протокол {{ $learningTypeName }}
            </span>
            <a href="{{ route('protocols.export-excel', $protocol->prot_id ?? $protocol->id) }}"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 0 16px 12px 16px; margin-bottom: -1px; font-size: 13px; font-weight: 600; color: #16a34a; text-decoration: none; border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s; font-family: system-ui, -apple-system, sans-serif;"
                onmouseover="this.style.borderBottom='2px solid #16a34a'; this.style.color='#15803d';"
                onmouseout="this.style.borderBottom='2px solid transparent'; this.style.color='#16a34a';">
                📊 <span style="border-left: 1px solid #e5e7eb; padding-left: 8px; margin-left: 2px;">Сформировать Excel</span>
            </a>
        </div>

        <!-- ОТЧЕТ -->
        <form action="{{ route('protocols.report.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_protocol" value="{{ $protocol->prot_id }}">
            <div x-show="activeTab === 'report'" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; padding: 12px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 12px; color: #4b5563;">
                    <div><b>Вид обучения:</b> <span class="text-gray-900 font-semibold">{{ $protocol->demand?->learningType?->name ?? '—' }}</span></div>
                    <div><b>Форма обучения:</b> <span class="text-gray-900 font-semibold">{{ $protocol->demand?->education_form ?? 'дистанционное' }}</span></div>
                    <div><b>Программа:</b> <span class="text-gray-900 font-semibold">{{ $protocol->demand?->course?->course ?? '—' }}</span></div>
                    <div><b>Период обучения (план):</b> <span class="text-gray-900 font-semibold">с {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }} по {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}</span></div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 12px;">
                    <!-- Ряд 1: Ф.И.О. специалиста ООО -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Ф.И.О. специалиста:</label>
                        <select name="user_oo_fio" style="width: 100%; max-width:385px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 34px; font-size: 13px;">
                            <option value="">— Выберите специалиста ООО —</option>
                            @foreach($refCurators as $curator)
                            <option value="{{ $curator->fio }}"
                                {{ (isset($report) && $report->specialist == $curator->fio) || (!isset($report) && auth()->user()->name == $curator->fio) ? 'selected' : '' }}>
                                {{ $curator->fio }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Ряд 2: Распоряжение № / Приказ № -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px; position: relative;">
                        <label style="font-weight: 500; color: #374151;">Распоряжение №/ Приказ №:</label>
                        <div style="display: flex; gap: 6px; align-items: center; position: relative;"
                            x-data="{ 
                                searchStr: '{{ $protocol->order_num }}', 
                                suggestions: [], 
                                openList: false,
                                fetchOrders() {
                                    if(this.searchStr.length < 1) { this.suggestions = []; this.openList = false; return; }
                                    fetch(`/api/orders/search?q=${encodeURIComponent(this.searchStr)}`)
                                        .then(r => r.json())
                                        .then(data => {
                                            this.suggestions = data;
                                            this.openList = this.suggestions.length > 0;
                                        });
                                },
                                selectOrder(order) {
                                    this.searchStr = order.order_number;
                                    document.getElementById('order_date_input').value = order.order_date;
                                    this.openList = false;
                                }
                             }"
                            @click.away="openList = false">

                            <div style="position: relative; width: 100%; max-width: 180px;">
                                <input type="text" name="order_num" autocomplete="off"
                                    x-model="searchStr"
                                    @input.debounce.250ms="fetchOrders()"
                                    @focus="if(searchStr.length > 0) fetchOrders()"
                                    style="width: 100%; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px; box-sizing: border-box;">

                                <div x-show="openList"
                                    style="position: absolute; top: 38px; left: 0; width: 280px; background: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 99999; max-height: 200px; overflow-y: auto;"
                                    x-cloak>
                                    <template x-for="order in suggestions" :key="order.id">
                                        <div @click="selectOrder(order)"
                                            style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f3f4f6; transition: background 0.15s;"
                                            onmouseover="this.style.backgroundColor='#f3f4f6'"
                                            onmouseout="this.style.backgroundColor='#ffffff'">
                                            <div style="font-weight: 600; color: #111827;" x-text="order.order_number"></div>
                                            <div style="font-size: 10px; color: #6b7280;" x-text="`от ${new Date(order.order_date).toLocaleDateString('ru-RU')}`"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <span style="padding: 5px 10px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; font-weight: 600; font-size: 11px; height: 34px; display: flex; align-items: center;">от</span>

                            <input type="date" name="order_date" id="order_date_input" value="{{ $protocol->order_date }}" style="width: 160px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px;">
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
                    <!-- Ряд 5: Фактическая дата начала -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Фактическая Дата начала обучения (для титульного):</label>
                        <input type="date" name="start_date_fact" value="{{ $protocol->date_start }}" style="width: 160px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; height: 34px;">
                    </div>
                    <!-- Блок Часов по программе -->
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
                    <!-- Блок Категория обучаемых -->
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 12px;">
                        <span style="display: block; font-weight: 700; color: #4b5563; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; margin-bottom: 12px; text-align: center; background: #f3f4f6; padding: 4px; border-radius: 4px;">Категория обучаемых</span>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; text-align: center;">
                            <div style="background: #fafafa; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px;">
                                <div style="color: #6b7280; font-size: 11px; margin-bottom: 2px;">Рабочие</div>
                                <div style="font-size: 16px; font-weight: 700; color: #111827;">{{ collect($protocol->demand?->employees)->where('position_type', 'Рабочий')->count() }}</div>
                            </div>
                            <div style="background: #fafafa; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px;">
                                <div style="color: #6b7280; font-size: 11px; margin-bottom: 2px;">Руководители</div>
                                <div style="font-size: 16px; font-weight: 700; color: #111827;">{{ collect($protocol->demand?->employees)->where('position_type', 'Руководитель')->count() }}</div>
                            </div>
                            <div style="background: #fafafa; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px;">
                                <div style="color: #6b7280; font-size: 11px; margin-bottom: 2px;">Специалисты</div>
                                <div style="font-size: 16px; font-weight: 700; color: #111827;">{{ collect($protocol->demand?->employees)->where('position_type', 'Специалист')->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Блок Результаты обучения и пол -->
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 10px; margin-top: 14px; font-family: system-ui, -apple-system, sans-serif;">
                        <span style="display: block; font-weight: 700; color: #4b5563; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; margin-bottom: 10px; text-align: center; background: #f3f4f6; padding: 4px; border-radius: 4px;">Результаты обучения и пол</span>

                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 10px; text-align: center;">
                            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0 12px; height: 34px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                <span style="color: #6b7280; font-size: 11px; font-weight: 500;">Явилось на экзамены:</span>
                                <strong style="font-size: 13px; color: #111827;">{{ $report ? $report->Show : count($protocol->demand?->employees ?? []) }}</strong>
                            </div>
                            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0 12px; height: 34px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                <span style="color: #059669; font-size: 11px; font-weight: 600;">Сдали:</span>
                                <strong style="font-size: 13px; color: #059669;">{{ $report ? $report->passed : count($protocol->demand?->employees ?? []) }}</strong>
                            </div>
                            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0 12px; height: 34px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                <span style="color: #6b7280; font-size: 11px; font-weight: 500;">Кол-во мужчин:</span>
                                <strong style="font-size: 13px; color: #111827;">
                                    {{ collect($protocol->demand?->employees ?? [])->where('gender_key', '1')->count() }}
                                </strong>
                            </div>

                        </div>

                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; text-align: center;">
                            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0 12px; height: 34px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                <span style="color: #dc2626; font-size: 11px; font-weight: 600;">Не явилось (неявка):</span>
                                <strong style="font-size: 13px; color: #dc2626;">{{ $report ? $report->noshow : 0 }}</strong>
                            </div>
                            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0 12px; height: 34px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                <span style="color: #dc2626; font-size: 11px; font-weight: 600;">Не сдали:</span>
                                <strong style="font-size: 13px; color: #dc2626;">{{ $report ? $report->nopassed : 0 }}</strong>
                            </div>

                            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0 12px; height: 34px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                <span style="color: #6b7280; font-size: 11px; font-weight: 500;">Кол-во женщин:</span>
                                <strong style="font-size: 13px; color: #111827;">
                                    {{ collect($protocol->demand?->employees ?? [])->where('gender_key', '2')->count() }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Блок Группы предприятий -->
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
                    <!-- Ряд Множественный выбор Преподавателей -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;">
                        <label style="font-weight: 500; color: #374151;">Преподаватель:</label>
                        <select name="teachers[]" symmetric-multiple="true" multiple style="width: 100%; max-width: 320px; padding: 6px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 68px;">
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ $protocol->demand?->teacher_id == $t->id ? 'selected' : '' }}>{{ $t->fio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Ряд Выбор куратора УРП -->
                    <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                        <label style="font-weight: 500; color: #374151;">Куратор УРП:</label>
                        <select name="curator_id" style="width: 100%; max-width: 320px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; height: 34px;">
                            <option value="{{ $protocol->demand?->curator_id }}">{{ $protocol->demand?->curator?->fio ?? '—' }}</option>
                        </select>
                    </div>
                    <!-- РЯД УПРАВЛЕНИЕ СОСТАВОМ КОМИССИИ ПРОТОКОЛА -->
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 16px; margin-top: 12px; display: flex; flex-direction: column; gap: 14px;">
                        <span style="display: block; font-weight: 700; color: #1e3a8a; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; background: #eff6ff; padding: 6px; border-radius: 4px; text-align: center;">Управление составом аттестационной комиссии</span>
                        <!-- Выбор Председателя -->
                        <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                            <label style="font-weight: 600; color: #374151;">Председатель комиссии:</label>
                            <select name="chairman_fio" x-model="chairman" style="width:100%; padding:6px; border:1px solid #d1d5db; border-radius:6px; max-width:420px;">
                                <option value="-">-- Выберите председателя --</option>
                                @foreach($allComissionMembers as $member)
                                    @php
                                        $isChairman = $currentComission->where('note', 'Председатель')
                                            ->filter(function($row) use ($member) {
                                                return trim(mb_strtolower($row->fio)) === trim(mb_strtolower($member->fio));
                                            })->first();
                                    @endphp
                                    <option value="{{ $member->fio }}" {{ $isChairman ? 'selected' : '' }}>
                                        {{ $member->fio }} ({{ $member->position }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Выбор Заместителя председателя -->
                        <div style="display: grid; grid-template-columns: 240px 1fr; align-items: center; gap: 12px;">
                            <label style="font-weight: 600; color: #374151;">Заместитель председателя:</label>
                            <select name="deputy_fio" x-model="deputy" style="width:100%; padding:6px; border:1px solid #d1d5db; border-radius:6px; max-width:420px;">
                                <option value="-">-- Выберите заместителя --</option>
                                @foreach($allComissionMembers as $member)
                                    @php
                                        $isDeputy = $currentComission->where('note', 'Заместитель председателя')->where('fio', $member->fio)->first();
                                    @endphp
                                    <option value="{{ $member->fio }}" {{ $isDeputy ? 'selected' : '' }}>
                                        {{ $member->fio }} ({{ $member->position }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Множественный выбор Членов комиссии -->
                        <div style="display: grid; grid-template-columns: 240px 1fr; align-items: start; gap: 12px;">
                            <label style="font-weight: 600; color: #374151; margin-top: 4px;">Члены комиссии (выберите):</label>
                            <div style="display: flex; flex-direction: column; gap: 8px; width: 100%; max-width: 600px;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    @foreach($allComissionMembers as $member)
                                        @php
                                            $isMemberChecked = $currentComission->where('note', 'Член комиссии')->where('fio', $member->fio)->first();
                                        @endphp
                                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #374151; cursor: pointer;">
                                            <input type="checkbox" name="comission_members[]" value="{{ $member->id }}" {{ $isMemberChecked ? 'checked' : '' }}
                                                @change="toggleMember('{{ $member->fio }}')"
                                                style="border-radius: 4px; border-color: #d1d5db; color: #3b82f6;">
                                            <span>{{ $member->fio }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div style="font-size: 11px; color: #6b7280; display: flex; align-items: center; gap: 4px;">
                                    📋 Выбрано в протокол: <strong style="color: #4f46e5" x-text="selectedMembers.length ? selectedMembers.join(', ') : '—'"></strong>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- СОДЕРЖИМОЕ ВКЛАДКИ 2: СПИСОК -->
            <div x-show="activeTab === 'list'" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" x-cloak>
                <!-- ВЕРХНЯЯ ШАПКА БЛАНКА -->
                <div style="font-family: 'Arial', sans-serif; font-size: 11px; color: #374151; line-height: 1.5; margin-bottom: 20px; text-align: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 14px;">
                    <div style="font-weight: bold; font-size: 12px; margin-bottom: 4px;">Приложение №1</div>
                    <div style="margin-bottom: 12px;">к Распоряжению № <span style="font-weight: 700; text-decoration: underline;">{{ $protocol->order_num ?? '_____' }}</span></div>

                    <div style="max-width: 800px; margin: 0 auto 12px auto; font-weight: 600; line-height: 1.3; color: #111827;">
                        Программа обучения: {{ $protocol->demand?->course?->course ?? '—' }}
                    </div>

                    <!-- Параметры группы -->
                    <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; margin-top: 8px; font-size: 12px;">
                        <div><b>Группа №:</b> <span style="color: #4f46e5; font-weight: 700;">{{ $protocol->prot_num }}</span></div>
                        <div><b>Ответственный за обучение:</b> <span style="font-weight: 600;">{{ $protocol->demand?->teacher?->fio ?? '-' }}</span></div>
                        <div style="display: flex; gap: 16px;">
                            <div><b>Дата начала обучения:</b> {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }}</div>
                            <div><b>Дата окончания обучения:</b> {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}</div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 16px; display: flex; align-items: center;">
                    <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: #111827;">Реестр обучаемых сотрудников (согласно заявке)</h3>
                    <span style="margin-left: auto; font-size: 12px; color: #6b7280;">Всего в группе: <b class="text-gray-900">{{ count($protocol->demand?->employees ?? []) }} чел.</b></span>
                </div>

                <div style="overflow-x: auto; width: 100%; border: 1px solid #e5e7eb; border-radius: 6px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: left; color: #374151; min-width: 1200px;">
                        <thead>
                            <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-weight: 600;">
                                <th style="padding: 10px 8px; width: 40px; text-align: center;">№</th>
                                <th style="padding: 10px 8px; width: 90px;">№ табельный</th>
                                <th style="padding: 10px 8px; width: 200px;">Фамилия Имя Отчество</th>
                                <th style="padding: 10px 8px; width: 160px;">Должность</th>
                                <th style="padding: 10px 8px; width: 60px; text-align: center;">Разряд</th>
                                <th style="padding: 10px 8px; width: 140px;">Предприятие</th>
                                <th style="padding: 10px 8px; width: 160px;">Место работы (цех, отдел)</th>
                                @if(isset($matrixNum) && $matrixNum == 3)
                                <th style="padding: 10px 8px; width: 180px;">Процессное управление</th>
                                <th style="padding: 10px 8px; width: 100px;">Контакты</th>
                                <th style="padding: 10px 8px; width: 90px; text-align: center;">Дата рождения</th>
                                <th style="padding: 10px 8px; width: 110px; text-align: center;">СНИЛС</th>
                                @endif
                                <th style="padding: 10px 8px; width: 60px; text-align: center;">Пол</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($protocol->demand?->employees ?? [] as $index => $employee)
                            @php
                            $empTabNum = (string)($employee->tab_num ?? $employee->tab_number ?? '');
                            $sapRow = !empty($empTabNum) ? $sapUsers->get($empTabNum) : null;

                            // 1. ФИО
                            $origFio = trim($employee->last_name . ' ' . $employee->first_name . ' ' . ($employee->middle_name ?? ''));
                            if(empty(trim($origFio))) { $origFio = trim($employee->fio ?? $employee->name ?? ''); }
                            $sapFio = $sapRow ? trim($sapRow->fullname ?? $sapRow->fio ?? $origFio) : $origFio;
                            $isFioChanged = (!empty($sapFio) && mb_strtolower($origFio) !== mb_strtolower($sapFio));

                            // 2. Должность
                            $origPosition = trim($employee->position ?? '');
                            $sapPosition = $sapRow ? trim($sapRow->position ?? $origPosition) : $origPosition;
                            $isPositionChanged = (!empty($sapPosition) && mb_strtolower($origPosition) !== mb_strtolower($sapPosition));

                            // 3. Предприятие
                            $origCompany = trim($employee->level_3_name ?? '');
                            $sapCompany = $sapRow ? trim($sapRow->level_3_name ?? $origCompany) : $origCompany;
                            $isCompanyChanged = (!empty($sapCompany) && mb_strtolower($origCompany) !== mb_strtolower($sapCompany));

                            // 4. Подразделение/Цех
                            $origDept = trim($employee->level_4_name ?? '');
                            $sapDept = $sapRow ? trim($sapRow->level_4_name ?? $origDept) : $origDept;
                            $isDeptChanged = (!empty($sapDept) && mb_strtolower($origDept) !== mb_strtolower($sapDept));

                            // 5. Разряд
                            $origRank = trim($employee->rank ?? '');
                            $sapRank = $sapRow ? trim($sapRow->rank ?? $origRank) : $origRank;
                            $isRankChanged = (!empty($sapRank) && mb_strtolower($origRank) !== mb_strtolower($sapRank));
                            @endphp
                            <tr style="border-bottom: 1px solid #e5e7eb; height: 40px;">
                                <td style="padding: 8px; text-align: center; color: #6b7280;">{{ $index + 1 }}</td>
                                <td style="padding: 8px; font-family: monospace; color: #374151;">{{ $empTabNum }}</td>

                                <!-- ФИО -->
                                <td style="padding: 8px; color: #111827;">
                                    <span style="{{ $isFioChanged ? 'color: #b91c1c; font-weight: bold;' : '' }}">{{ $origFio }}</span>
                                    @if($isFioChanged)
                                    <span style="color: #9ca3af; font-size: 11px; font-weight: normal; margin-left: 4px;">/ {{ $sapFio }}</span>
                                    @endif
                                </td>

                                <!-- ДОЛЖНОСТЬ -->
                                <td style="padding: 8px;">
                                    <span style="{{ $isPositionChanged ? 'color: #ea580c; font-weight: bold;' : 'color: #374151;' }}">{{ $origPosition }}</span>
                                    @if($isPositionChanged)
                                    <span style="color: #9ca3af; font-size: 11px; margin-left: 4px;">/ {{ $sapPosition }}</span>
                                    @endif
                                </td>

                                <!-- РАЗРЯД -->
                                <td style="padding: 8px; text-align: center;">
                                    <span style="{{ $isRankChanged ? 'color: #ea580c; font-weight: bold;' : 'color: #374151;' }}">{{ $origRank ?: '—' }}</span>
                                    @if($isRankChanged)
                                    <span style="color: #9ca3af; font-size: 11px; margin-left: 2px;">/ {{ $sapRank }}</span>
                                    @endif
                                </td>

                                <!-- ПРЕДПРИЯТИЕ -->
                                <td style="padding: 8px;">
                                    <span style="{{ $isCompanyChanged ? 'color: #ea580c; font-weight: bold;' : 'color: #374151;' }}">{{ $origCompany }}</span>
                                    @if($isCompanyChanged)
                                    <span style="color: #9ca3af; font-size: 11px; margin-left: 4px;">/ {{ $sapCompany }}</span>
                                    @endif
                                </td>

                                <!-- МЕСТО РАБОТЫ (ЦЕХ) -->
                                <td style="padding: 8px;">
                                    <span style="{{ $isDeptChanged ? 'color: #ea580c; font-weight: bold;' : 'color: #374151;' }}">{{ $origDept }}</span>
                                    @if($isDeptChanged)
                                    <span style="color: #9ca3af; font-size: 11px; margin-left: 4px;">/ {{ $sapDept }}</span>
                                    @endif
                                </td>

                                @if(isset($matrixNum) && $matrixNum == 3)
                                <td style="padding: 8px; color: #4b5563; font-size: 10px;">{{ $employee->process_management ?? '—' }}</td>
                                <td style="padding: 8px; color: #6b7280;">{{ $employee->phone ?? '—' }}</td>
                                <td style="padding: 8px; text-align: center;">{{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('d.m.Y') : '—' }}</td>
                                <td style="padding: 8px; text-align: center; font-family: monospace;">{{ $employee->snils ?? '—' }}</td>
                                @endif
                                <td style="padding: 8px; text-align: center;"><span style="padding: 2px 6px; background: #f3f4f6; border-radius: 4px; font-size: 10px; font-weight: 600;">{{ $employee->gender ?? '—' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" style="padding: 24px; text-align: center; color: #9ca3af;">В данной заявке список сотрудников пуст</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- СОДЕРЖИМОЕ ВКЛАДКИ 3: ПРОТОКОЛ-->
            <div x-show="activeTab === 'protocol'" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" x-cloak>
                <!-- ОТ -->
                @if(isset($matrixNum) && $matrixNum == 4)

                <div style="max-width: 1500px; margin: 0 auto; background: #fff; padding: 10px; border: 1px solid #d1d5db; position: relative;">

                    <!-- Правый верхний угол: Ссылка на ЕИСОТ -->
                    <div style="position: absolute; top: 15px; right: 20px; text-align: right; font-size: 11px; line-height: 1.3; font-weight: bold; color: #111827;">
                        Приложение к протоколу № {{ $protocol->prot_num }} от {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '____' }}г.<br>
                        <span style="font-weight: 500; font-style: italic; color: #4b5563;">Факт прохождения проверки знаний в ЕИСОТ.</span>
                    </div>

                    <!-- Центральный официальный заголовок Минтруда -->
                    <div style="text-align: center; font-weight: 800; font-size: 15px; letter-spacing: 0.03em; margin-bottom: 25px; line-height: 1.4; color: #000000; padding-top: 15px;">
                        ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ<br>
                        «РН-ЮГАНСКНЕФТЕГАЗ»<br>
                        (ООО «РН-ЮГАНСКНЕФТЕГАЗ»)<br>
                        <span style="font-size: 16px; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 2px; margin-top: 4px;">УЧЕБНЫЙ ЦЕНТР</span>
                    </div>

                    <!-- Номерной блок протокола -->
                    <div style="text-align: center; font-size: 14px; margin-bottom: 15px; font-weight: bold;">
                        ПРОТОКОЛ &nbsp; № &nbsp; <span style="border-bottom: 1px solid #000; padding: 0 15px;">{{ $protocol->prot_num }}</span> &nbsp; от &nbsp; <span style="border-bottom: 1px solid #000; padding: 0 20px;">{{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '____' }}</span>
                    </div>

                    <div style="font-size: 11px; text-align: center; margin-bottom: 14px; font-style: italic; color: #374151;">
                        заседания комиссии по проверке знаний требований охраны труда, назначенной распоряжением № <span style="font-weight: 700;">{{ $protocol->order_num ?? '_____' }}</span> от <span style="font-weight: 700;">{{ $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->format('d.m.Y') : '____' }}</span>
                    </div>

                    <div style="font-size: 11px; margin-bottom: 20px; color: #111827; display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; background: #f9fafb; padding: 8px; border-radius: 6px; border: 1px solid #e5e7eb;">
                        <div><b>Председатель:</b> <span x-text="chairman"></span></div>
                        <div><b>Заместитель:</b> <span x-text="deputy"></span></div>
                        <div><b>Комиссия:</b> <span x-text="selectedMembers.length ? selectedMembers.join(', ') : '—'"></span></div>
                    </div>

                    <!-- Данные учебной группы и курса -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; font-size: 11px; margin-bottom: 16px; background: #fff; padding: 4px;">
                        <div><b>По проверке знаний обучающихся группы №:</b> <span style="color: #4f46e5; font-weight: 700;">{{ $protocol->prot_num }}</span></div>
                        <div><b>Программа обучения:</b> <span style="font-weight: 700; color: #111827;">«{{ $protocol->demand?->course?->course ?? '—' }}»</span></div>
                    </div>

                    <!-- Параметры фактических часов обучения по Охране Труда -->
                    <div style="font-size: 11px; border: 1px solid #7f8c8d; border-radius: 6px; overflow: hidden; margin-bottom: 20px; max-width: 450px;">
                        <div style="display: grid; grid-template-columns: 280px 1fr; border-bottom: 1px solid #e5e7eb; padding: 6px 12px; background: #f9fafb;">
                            <div>Начало обучения / Окончание обучения:</div>
                            <div style="font-weight: 700; text-align: center;">{{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 280px 1fr; border-bottom: 1px solid #e5e7eb; padding: 6px 12px;">
                            <div>Количество часов по программе:</div>
                            <div style="font-weight: 700; text-align: center;">{{ $protocol->program_hours ?? 8 }}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 280px 1fr; border-bottom: 1px solid #e5e7eb; padding: 6px 12px;">
                            <div>Количество часов теоретического обучения:</div>
                            <div style="font-weight: 700; text-align: center;">{{ $protocol->theory_hours ?? 4 }}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 280px 1fr; padding: 6px 12px;">
                            <div>Количество часов практического обучения:</div>
                            <div style="font-weight: 700; text-align: center;">{{ $protocol->practice_hours ?? 4 }}</div>
                        </div>
                    </div>

                    <!-- таблица выставления оценок ОТ -->
                    <div style="overflow-x: auto; width: 100%; margin-bottom: 20px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px; color: #000000; border: 2px solid #000000; table-layout: fixed;">
                            <thead>
                                <!-- Строка 1: Главные заголовки шапки -->
                                <tr style="background-color: #f2f2f2; text-align: center; font-weight: bold; border-bottom: 1px solid #000000;">
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 35px;">№ пп</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 85px;">№ табельный</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 140px;">Фамилия Имя Отчество</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 150px;">Должность</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 160px;">Наименование подразделения (цех, участок)</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 120px;">Предприятие</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 125px; color: #1e3a8a;">Результат проверки знаний</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 120px; color: #b91c1c;">Регистрационный номер ЕИСОТ</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 75px;">Подпись</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 85px;">Начало обуч. (факт)</th>
                                    <th style="border-right: 1px solid #000000; padding: 6px; width: 85px;">Оконч. обуч. (факт)</th>
                                    <th style="padding: 6px; width: 60px;">Пройдено (час.)</th>
                                </tr>
                                <tr style="background-color: #e6e6e6; text-align: center; font-weight: bold; font-size: 10px; border-bottom: 2px solid #000000; height: 18px;">
                                    <td style="border-right: 1px solid #000000;">1</td>
                                    <td style="border-right: 1px solid #000000;">2</td>
                                    <td style="border-right: 1px solid #000000;">3</td>
                                    <td style="border-right: 1px solid #000000;">4</td>
                                    <td style="border-right: 1px solid #000000;">5</td>
                                    <td style="border-right: 1px solid #000000;">6</td>
                                    <td style="border-right: 1px solid #000000;">7</td>
                                    <td style="border-right: 1px solid #000000;">8</td>
                                    <td style="border-right: 1px solid #000000;">9</td>
                                    <td style="border-right: 1px solid #000000;">10</td>
                                    <td style="border-right: 1px solid #000000;">11</td>
                                    <td>12</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($protocol->demand?->employees ?? [] as $index => $employee)
                                @php
                                // Вытаскиваем сохраненную строку результатов из СУБД для сопоставления по СНИЛС или табельному
                                $empSnilsRaw = $employee->pfr_certificate ?? '';
                                $empSnilsClean = !empty($empSnilsRaw) ? preg_replace('/[^0-9]/', '', $empSnilsRaw) : '';
                                $empTabNum = (string)($employee->tab_number ?? '');

                                $dbRow = null;
                                if (!empty($empSnilsClean)) { $dbRow = $savedResults->get($empSnilsClean); }
                                if (!$dbRow && !empty($empTabNum)) { $dbRow = $savedResults->get($empTabNum); }
                                if (!$dbRow) { $dbRow = $savedResults->get((string)$employee->id); }

                                $studentKey = $employee->id;
                                $empFullName = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
                                if(empty($empFullName)) { $empFullName = $employee->fio ?? $employee->name ?? '—'; }

                                // Определяем текущее значение оценки в ЕИСОТ (по дефолту удовлетворительно)
                                $currentMark = $dbRow?->markitog ?? 'удовлетворительно';
                                @endphp
                                <tr style="border-bottom: 1px solid #000000; min-height: 38px;">
                                    <!-- Колонка 1: Порядковый номер -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; text-align: center; font-weight: bold; color: #4b5563;">{{ $index + 1 }}</td>

                                    <!-- Колонка 2: Табельный номер -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; text-align: center; font-family: monospace;">{{ $employee->tab_number ?? '—' }}</td>

                                    <!-- Колонка 3: ФИО сотрудника -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; font-weight: bold; color: #111827;">{{ $empFullName }}</td>

                                    <!-- Колонка 4: Должность -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; font-size: 10px; line-height: 1.3;">{{ $employee->position ?? '—' }}</td>

                                    <!-- Колонка 5: Подразделение (Цех/Участок) -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; font-size: 10px; line-height: 1.3; color: #374151;">{{ $employee->level_4_name ?? $employee->department ?? '—' }}</td>

                                    <!-- Колонка 6: Предприятие -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; font-size: 10px;">{{ $employee->level_3_name ?? $employee->company ?? '—' }}</td>

                                    <!-- Колонка 7: Результат проверки знаний -->
                                    <td style="border-right: 1px solid #000000; padding: 4px; text-align: center;">
                                        <select name="results[{{ $studentKey }}][status]" style="width: 100%; padding: 4px; font-size: 11px; font-weight: bold; border: 1px solid #d1d5db; border-radius: 4px; color: #000000; background-color: #ffffff;">
                                            <option value="удовлетворительно" {{ $currentMark === 'удовлетворительно' ? 'selected' : '' }}>удовлетворительно</option>
                                            <option value="неудовлетворительно" {{ $currentMark === 'неудовлетворительно' ? 'selected' : '' }}>неудовлетворительно</option>
                                            <option value="неявка" {{ $currentMark === 'неявка' ? 'selected' : '' }}>неявка</option>
                                        </select>
                                    </td>

                                    <!-- Колонка 8: Регистрационный номер записи в ЕИСОТ -->
                                    <td style="border-right: 1px solid #000000; padding: 4px; text-align: center;">
                                        <input type="text" name="results[{{ $studentKey }}][eisot_num]"
                                            value="{{ $dbRow?->regnumber ?? $dbRow?->documentnumber ?? '' }}"
                                            placeholder="211509345"
                                            style="width: 100%; padding: 4px; font-size: 11px; font-weight: bold; border: 1px solid #b91c1c; border-radius: 4px; text-align: center; color: #b91c1c; bg: #fff5f5;">
                                    </td>

                                    <!-- Колонка 9: Место подписи -->
                                    <td style="border-right: 1px solid #000000; padding: 6px; text-align: center; color: #9ca3af; font-size: 10px; font-style: italic;">подпись</td>

                                    <!-- Колонка 10: Дата начала (факт) -->
                                    <td style="border-right: 1px solid #000000; padding: 4px; text-align: center; vertical-align: middle;">
                                        <input type="date" name="results[{{ $studentKey }}][start_fact]"
                                            value="{{ $dbRow?->begindatefact ?? $protocol->date_start ? \Carbon\Carbon::parse($dbRow?->begindatefact ?? $protocol->date_start)->format('Y-m-d') : '' }}"
                                            class="no-datepicker"
                                            data-datepicker="false"
                                            style=" width: 100%; font-size: 11px; font-family: 'Arial', sans-serif; font-weight: bold; text-align: center; color: #111827; background: transparent; cursor: pointer; box-sizing: border-box;">
                                    </td>

                                    <!-- Колонка 11: Дата окончания (факт) -->
                                    <td style="border-right: 1px solid #000000; padding: 4px; text-align: center;">
                                        <input type="date" name="results[{{ $studentKey }}][end_fact]"
                                            value="{{ $dbRow?->enddatefact ?? $protocol->date_end ? \Carbon\Carbon::parse($dbRow?->enddatefact ?? $protocol->date_end)->format('Y-m-d') : '' }}"
                                            style="all: unset; width: 100%; padding: 2px; font-size: 10px; border: 1px solid #d1d5db; border-radius: 4px; background: #fff;">
                                    </td>

                                    <!-- Колонка 12: Фактические часы пройденного обучения -->
                                    <td style="padding: 4px; text-align: center;">
                                        <input type="number" name="results[{{ $studentKey }}][hours_fact]"
                                            value="{{ (int)($dbRow?->counthourfact ?? $protocol->program_hours ?? 8) }}"
                                            style="all: unset; width: 100%; padding: 4px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center; background: #fff;">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" style="padding: 24px; text-align: center; color: #9ca3af;">Список сотрудников для формирования протокола ОТ отсутствует</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Исполнитель-->
                        <div style="margin-top: 30px; font-family: 'Arial', sans-serif; color: #111827; font-size: 12px; display: flex; flex-direction: column; gap: 16px; border: 1px solid #e5e7eb; padding: 20px; border-radius: 6px; background-color: #fafafa; max-width: 350px;">
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; display: grid; grid-template-columns: 100px 150px 1fr; align-items: center; gap: 16px;">
                                <div style="font-weight: bold;">Исполнитель:</div>
                                <input type="text" name="commission[executor_fio]" value="-" style="padding: 4px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 12px; font-weight: 600; width: 100%; max-width: 250px; height: 28px; background: #fff;">
                            </div>
                        </div>
                    </div>

                    <!-- Мультимедийные курсы (matrix_num = 5) -->
                    @elseif(isset($matrixNum) && $matrixNum == 5)
                    <div style="max-width: 1200px; margin: 0 auto; background: #fff; padding: 10px; font-family: 'Arial', sans-serif; color: #000000;">

                        <!-- ВЕРХНЯЯ СЕТКА ШАПКИ БЛАНКА (ОТЧЕТ № / НОМЕРА / ДАТЫ ИЗ EXCEL) -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr 40px 1fr; gap: 10px; align-items: start; margin-bottom: 25px; text-align: center;">
                            <!-- Колонки 1-2: ОТЧЕТ № -->
                            <div style="text-align: right; padding-right: 15px;">
                                <div style="font-size: 16px; font-weight: 800; text-transform: uppercase;">ОТЧЕТ №</div>
                                <div style="font-size: 10px; color: #b91c1c; font-weight: bold; margin-top: 1px;">Приказ/Распоряжение</div>
                            </div>

                            <!-- Колонка 3: Номер группы и приказа -->
                            <div style="text-align: left; padding-left: 10px;">
                                <div style="font-size: 16px; font-weight: 800; color: #000000;">{{ $protocol->prot_num }}</div>
                                <div style="font-size: 11px; font-weight: bold; color: #374151; margin-top: 3px;">{{ $protocol->order_num ?? '-' }}</div>
                            </div>

                            <!-- Разделитель "от" -->
                            <div style="font-size: 13px; font-weight: bold; color: #000000; text-transform: lowercase; line-height: 1.3;">от<br><span style="font-size: 10px; color:#4b5563; font-weight:normal;">от</span></div>

                            <!-- Колонки 5-6: Даты формирования и назначения приказа -->
                            <div style="text-align: left; padding-left: 15px;">
                                <div style="font-size: 16px; font-weight: 800; color: #000000;">{{ $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->format('d.m.Y') : (\Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y')) }}</div>
                                <div style="font-size: 11px; font-weight: bold; color: #374151; margin-top: 3px;">{{ $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->format('d.m.Y') : '-' }}</div>
                            </div>
                        </div>

                        <!-- НАИМЕНОВАНИЕ ПРОГРАММЫ ОБУЧЕНИЯ -->
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div style="font-size: 11px; font-weight: bold; text-align: left; margin-bottom: 6px; padding-left: 4px;">Группа № {{ $protocol->prot_num }}</div>
                            <div style="font-size: 13px; font-weight: bold; color: #000000; border-bottom: 1px solid #000000; padding-bottom: 6px; text-align: center; letter-spacing: 0.01em;">
                                {{ $protocol->demand?->course?->course ?? '-' }}
                            </div>
                        </div>
                        <!-- ТАБЛИЧНАЯ ВЕДОМОСТЬ СВЕДЕНИЙ О ПРОХОЖДЕНИИ МУЛЬТИМЕДИЙНЫХ КУРСОВ -->
                        <div style="overflow-x: auto; width: 100%; margin-bottom: 20px;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 11px; color: #000000; border: 2px solid #000000;">
                                <thead>
                                    <tr style="background-color: #ffffff; text-align: center; font-weight: bold; border-bottom: 1px solid #000000; height: 28px;">
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 45px;">№ пп</th>
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 95px;">№ табельный</th>
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 220px;">Фамилия Имя Отчество</th>
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 240px;">Профессия (должность)</th>
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 65px;">Разряд</th>
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 200px;">Предприятие</th>
                                        <th style="border-right: 1px solid #000000; padding: 4px; width: 140px; color: #1e3a8a;">Сведения о прохождении обучения</th>
                                        <th style="padding: 4px; width: 90px;">Подпись</th>
                                    </tr>
                                    <tr style="background-color: #e6e6e6; text-align: center; font-weight: bold; font-size: 10px; border-bottom: 2px solid #000000; height: 18px; color: #333333;">
                                        <td style="border-right: 1px solid #000000;">1</td>
                                        <td style="border-right: 1px solid #000000;">2</td>
                                        <td style="border-right: 1px solid #000000;">3</td>
                                        <td style="border-right: 1px solid #000000;">4</td>
                                        <td style="border-right: 1px solid #000000;">5</td>
                                        <td style="border-right: 1px solid #000000;">6</td>
                                        <td style="border-right: 1px solid #000000;">7</td>
                                        <td>8</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($protocol->demand?->employees ?? [] as $index => $employee)
                                    @php
                                    $empSnilsRaw = $employee->pfr_certificate ?? '';
                                    $empSnilsClean = !empty($empSnilsRaw) ? preg_replace('/[^0-9]/', '', $empSnilsRaw) : '';
                                    $empTabNum = (string)($employee->tab_number ?? '');

                                    $dbRow = null;
                                    if (!empty($empSnilsClean)) { $dbRow = $savedResults->get($empSnilsClean); }
                                    if (!$dbRow && !empty($empTabNum)) { $dbRow = $savedResults->get($empTabNum); }
                                    if (!$dbRow) { $dbRow = $savedResults->get((string)$employee->id); }

                                    $studentKey = $employee->id;
                                    $empFullName = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
                                    if(empty($empFullName)) { $empFullName = $employee->fio ?? $employee->name ?? '—'; }

                                    $currentMark = $dbRow?->markitog ?? 'пройдено';
                                    @endphp
                                    <tr style="border-bottom: 1px solid #000000; min-height: 40px; text-align: left; vertical-align: middle;">
                                        <td style="border-right: 1px solid #000000; padding: 6px; text-align: center; font-weight: bold; color: #4b5563;">{{ $index + 1 }}</td>
                                        <td style="border-right: 1px solid #000000; padding: 6px; text-align: center; font-family: monospace;">{{ $employee->tab_number ?? $employee->tab_number ?? '—' }}</td>
                                        <td style="border-right: 1px solid #000000; padding: 6px; font-weight: bold; color: #111827;">{{ $empFullName }}</td>
                                        <td style="border-right: 1px solid #000000; padding: 6px; font-size: 10px; line-height: 1.3;">{{ $employee->position ?? '—' }}</td>
                                        <td style="border-right: 1px solid #000000; padding: 6px; text-align: center;">{{ $employee->rank ?? '—' }}</td>
                                        <td style="border-right: 1px solid #000000; padding: 6px; font-size: 10px;">{{ $employee->level_3_name ?? $employee->company ?? 'ООО «РН-Юганскнефтегаз»' }}</td>

                                        <!-- Сведения о прохождении мультимедийного курса -->
                                        <td style="border-right: 1px solid #000000; padding: 4px; text-align: center;">
                                            <select name="results[{{ $studentKey }}][status]" style="width: 100%; padding: 4px; font-size: 11px; font-weight: bold; border: 1px solid #d1d5db; border-radius: 4px; background-color: #ffffff; color: #000000; text-align: center;">
                                                <option value="пройдено" {{ $currentMark === 'пройдено' ? 'selected' : '' }}>пройдено</option>
                                                <option value="не пройдено" {{ $currentMark === 'не пройдено' ? 'selected' : '' }}>не пройдено</option>
                                                <option value="неявка" {{ $currentMark === 'неявка' ? 'selected' : '' }}>неявка</option>
                                            </select>
                                        </td>

                                        <td style="padding: 6px; text-align: center; color: #9ca3af; font-size: 11px;">-</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" style="padding: 20px; text-align: center; color: #6b7280; font-style: italic; background: #f9fafb;">Список сотрудников для формирования отчета по мультимедимйным курсам пуст</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- НИЖНИЙ БЛОК ПОДПИСЕЙ И ИСПОЛНИТЕЛЯ ДЛЯ МУЛЬТИМЕДИЙНЫХ КУРСОВ -->
                        <div style="margin-top: 25px; font-family: 'Arial', sans-serif; color: #111827; font-size: 12px; display: flex; flex-direction: column; gap: 14px; border: 1px solid #e5e7eb; padding: 20px; border-radius: 6px; background-color: #fafafa; max-width: 600px;">

                            <!-- Подпись Председателя -->
                            <div style="display: grid; grid-template-columns: 180px 140px 1fr; align-items: center; gap: 16px;">
                                <div style="font-weight: bold;">Председатель комиссии:</div>
                            </div>

                            <!-- Ввод Исполнителя -->
                            <div style="margin-top: 5px; display: grid; grid-template-columns: 180px 1fr; align-items: center; gap: 16px; font-size: 12px; border-top: 1px solid #e5e7eb; padding-top: 12px;">
                                <div style="font-weight: bold;">Исполнитель:</div>
                                <div style="font-weight: 600; color: #374151;">
                                    <input type="text" name="commission[executor_fio]" value="{{ $report->specialist ?? '' }}" style="border: none; border-bottom: 1px solid #d1d5db; padding: 2px 4px; font-size: 12px; font-weight: bold; color: #111827; outline: none; width: 220px; background: transparent;">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Курсы -->
                    @elseif(isset($matrixNum) && $matrixNum == 3)
                    <!-- Официальная шапка из Excel-шаблона -->
                    <div style="text-align: center; font-family: 'Arial', sans-serif; color: #111827; margin-bottom: 16px; line-height: 1.3;">
                        <div style="font-weight: 800; font-size: 13px; letter-spacing: 0.02em;">ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ</div>
                        <div style="font-weight: 900; font-size: 15px;">«РН-ЮГАНСКНЕФТЕГАЗ»</div>
                        <div style="font-size: 11px; font-weight: 600; color: #4b5563;">(ООО «РН-ЮГАНСКНЕФТЕГАЗ»)</div>
                        <div style="font-weight: 800; font-size: 13px; margin-top: 3px; text-decoration: underline;">УЧЕБНЫЙ ЦЕНТР</div>

                        <!-- Номер и Дата протокола в одну строку -->
                        <div style="display: flex; justify-content: center; align-items: center; gap: 40px; margin: 15px auto 6px auto; max-width: 600px;">
                            <h2 style="font-size: 15px; font-weight: 800; margin: 0; text-transform: uppercase;">ПРОТОКОЛ № {{ $protocol->prot_num }}</h2>
                            <span style="font-size: 12px; font-weight: 700;">от {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '«___» ____________ 202_ г.' }}</span>
                        </div>

                        <!-- Распоряжение -->
                        <div style="font-size: 11px; font-weight: 600; color: #374151;">
                            заседания комиссии, назначенной распоряжением № <span style="font-weight:700; border-b: 1px solid #000; padding: 0 10px;">{{ $protocol->order_num ?? '_____' }}</span> от <span style="font-weight:700; border-b: 1px solid #000; padding: 0 10px;">{{ $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->format('d.m.Y') : '___.___.202_' }}</span>
                        </div>

                        <!-- Линейный блок состава комиссии со скрина бланка -->
                        <div style="font-size: 11px; margin-top: 6px; color: #374151; display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">
                            <div>в составе председателя комиссии: <span style="font-weight: 700; text-decoration: underline;" x-text="chairman"></span></div>
                            <div>заместителя: <span style="font-weight: 600; text-decoration: underline;" x-text="deputy"></span></div>
                            <div>членов комиссии: <span style="font-weight: 600; text-decoration: underline;" x-text="selectedMembers.length ? selectedMembers.join(', ') : '—'"></span></div>
                        </div>
                    </div>

                    <!-- Описание программы обучения группы -->
                    <div style="font-size: 12px; line-height: 1.4; color: #374151; margin-bottom: 14px; display: flex; justify-content: center; gap: 24px; align-items: center; background: transparent; padding: 4px 0; flex-wrap: wrap;">
                        <div>
                            <b>по проверке знаний обучающихся группы №:</b>
                            <span style="color: #4f46e5; font-weight: 700; margin-left: 4px;">{{ $protocol->prot_num }}</span>
                        </div>
                        <div style="max-width: 700px; text-align: center;">
                            <b>Программа обучения:</b>
                            <span style="font-weight: 600; color: #111827; margin-left: 4px;">{{ $protocol->demand?->course?->course ?? '—' }}</span>
                        </div>
                    </div>

                    <!-- Двухколоночный мини-блок Дат и Общих Часов-->
                    <div style="display: flex; justify-content: center; gap: 48px; font-size: 12px; font-family: system-ui, -apple-system, sans-serif; color: #374151; margin-bottom: 20px; background: transparent; padding: 4px 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px;">
                        <div>
                            <b>Начало обучения:</b>
                            <span style="color: #111827; margin-left: 4px;">{{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }}</span>
                        </div>
                        <div>
                            <b>Окончание обучения:</b>
                            <span style="color: #111827; margin-left: 4px;">{{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}</span>
                        </div>
                        <div>
                            <b>Количество часов по программе:</b>
                            <span style="color: #4f46e5; font-weight: 700; margin-left: 4px; background: #eef2ff; padding: 2px 10px; border-radius: 4px;">{{ $protocol->program_hours ?? $protocol->hoursbyprogram ?? 0 }} ч.</span>
                        </div>
                    </div>

                    <!-- Таблица ведомости оценок Курсов -->
                    <div style="overflow-x: auto; width: 100%; border: 1px solid #7f8c8d;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: left; color: #111827; min-width: 1350px;">
                            <thead>
                                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #7f8c8d; text-align: center; font-weight: 700; height: 38px;">
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 40px;">№ пп</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 85px;">№ табельный</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 190px;">Фамилия Имя Отчество</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 160px;">Должность/профессия</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 60px;">Разряд</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 110px; color: #4338ca;">Итоговый зачет</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 110px;">Оценка за практ. обучение</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 100px;">Присваиваемый разряд</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 150px;">Предприятие</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 110px; color: #b91c1c;">Результат тестирования</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 80px;">Подпись</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 90px;">Начало обуч. (факт)</th>
                                    <th style="border-right: 1px solid #7f8c8d; padding: 4px; width: 90px;">Окончание обуч. (факт)</th>
                                    <th style="padding: 4px; width: 75px;">Пройдено (час.)</th>
                                </tr>
                                <tr style="background-color: #e5e7eb; border-bottom: 1px solid #7f8c8d; text-align: center; font-weight: 600; font-size: 10px; height: 18px; color: #555;">
                                    <td style="border-right: 1px solid #7f8c8d;">1</td>
                                    <td style="border-right: 1px solid #7f8c8d;">2</td>
                                    <td style="border-right: 1px solid #7f8c8d;">3</td>
                                    <td style="border-right: 1px solid #7f8c8d;">4</td>
                                    <td style="border-right: 1px solid #7f8c8d;">5</td>
                                    <td style="border-right: 1px solid #7f8c8d;">6</td>
                                    <td style="border-right: 1px solid #7f8c8d;">7</td>
                                    <td style="border-right: 1px solid #7f8c8d;">8</td>
                                    <td style="border-right: 1px solid #7f8c8d;">9</td>
                                    <td style="border-right: 1px solid #7f8c8d;">10</td>
                                    <td style="border-right: 1px solid #7f8c8d;">11</td>
                                    <td style="border-right: 1px solid #7f8c8d;">12</td>
                                    <td style="border-right: 1px solid #7f8c8d;">13</td>
                                    <td>14</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($protocol->demand?->employees ?? [] as $index => $employee)
                                @php
                                $empSnilsRaw = $employee->pfr_certificate ?? '';
                                $empSnilsClean = !empty($empSnilsRaw) ? preg_replace('/[^0-9]/', '', $empSnilsRaw) : '';
                                $empTabNum = (string)($employee->tab_number ?? '');

                                $dbRow = null;
                                if (!empty($empSnilsClean)) { $dbRow = $savedResults->get($empSnilsClean); }
                                if (!$dbRow && !empty($empTabNum)) { $dbRow = $savedResults->get($empTabNum); }
                                if (!$dbRow) { $dbRow = $savedResults->get((string)$employee->id); }

                                $studentKey = $employee->id;
                                @endphp
                                <tr style="border-bottom: 1px solid #7f8c8d; height: 36px;">
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; text-align: center; color: #6b7280;">{{ $index + 1 }}</td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; text-align: center;">{{ $employee->tab_number ?? '—' }}</td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; font-weight: 700;">{{ $employee->last_name ?? '—' }} {{ $employee->first_name ?? '—' }}</td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; font-size: 10px;">{{ $employee->position ?? '—' }}</td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; text-align: center;">{{ $employee->rank ?? '—' }}</td>
                                    <!-- 6. селект Итогового зачета -->
                                    <td style="border-right: 1px solid #7f8c8d; padding: 4px; text-align: center;">
                                        <select name="results[{{ $employee }}][status]" style="width: 100%; padding: 3px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px;">
                                            <option value="зачет" {{ ($dbRow?->markitog ?? 'зачет') == 'зачет' ? 'selected' : '' }}>зачет</option>
                                            <option value="незачет" {{ ($dbRow?->markitog ?? '') == 'незачет' ? 'selected' : '' }}>незачет</option>
                                            <option value="неявка" {{ ($dbRow?->markitog ?? '') == 'неявка' ? 'selected' : '' }}>неявка</option>
                                        </select>
                                    </td>
                                    <!-- 7. инпут Оценки за практику -->
                                    <td style="border-right: 1px solid #7f8c8d; padding: 4px;">
                                        <input type="text" name="results[{{ $employee->id }}][practice_mark]"
                                            value="{{ $dbRow?->markpractic ?? '' }}"
                                            placeholder="отл / 5" style="width: 100%; padding: 4px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center;">
                                    </td>
                                    <!-- 8. инпут Присваиваемого разряда -->
                                    <td style="border-right: 1px solid #7f8c8d; padding: 4px;">
                                        <input type="number" name="results[{{ $employee->id }}][assigned_rank]"
                                            value="{{ $dbRow?->assignedgrade ?? '' }}"
                                            style="width: 100%; padding: 4px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center;">
                                    </td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; font-size: 10px;">{{ $employee->level_3_name ?? '—' }}</td>
                                    <!-- 10. результат тестирования (%) -->
                                    <td style="border-right: 1px solid #7f8c8d; padding: 4px;">
                                        <input type="text" name="results[{{ $employee }}][test_result]"
                                            value="{{ $dbRow?->resulttest ?? '' }}"
                                            placeholder="90%" style="width: 100%; padding: 4px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center; font-weight: 600;">
                                    </td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 6px; text-align: center; color: #9ca3af; font-size: 10px; font-style: italic;">место подписи</td>

                                    <td style="border-right: 1px solid #7f8c8d; padding: 4px; text-align: center;">
                                        <input type="date" name="results[{{ $employee }}][start_fact]" value="{{ $dbRow?->begindatefact ?? $protocol->date_start ? \Carbon\Carbon::parse($dbRow?->begindatefact ?? $protocol->date_start)->format('Y-m-d') : '' }}" style="width: 100%; padding: 2px; font-size: 10px; border: 1px solid #d1d5db; border-radius: 4px;">
                                    </td>
                                    <td style="border-right: 1px solid #7f8c8d; padding: 4px; text-align: center;">
                                        <input type="date" name="results[{{ $employee }}][end_fact]" value="{{ $dbRow?->enddatefact ?? $protocol->date_end ? \Carbon\Carbon::parse($dbRow?->enddatefact ?? $protocol->date_end)->format('Y-m-d') : '' }}" style="width: 100%; padding: 2px; font-size: 10px; border: 1px solid #d1d5db; border-radius: 4px;">
                                    </td>
                                    <td style="padding: 4px; text-align: center;">
                                        <input type="number" name="results[{{ $employee }}][hours_fact]" value="{{ (int)($dbRow?->counthourfact ?? $protocol->program_hours ?? 0) }}" style="width: 55px; padding: 4px; font-size: 11px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center;">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14" style="padding: 24px; text-align: center; color: #9ca3af;">Список сотрудников для формирования протокола Курсов отсутствует</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- БЛОК ПОДПИСЕЙ И ДОЛЖНОСТНЫХ ЛИЦ КОМИССИИ -->
                        <div style="margin-top: 30px; font-family: 'Arial', sans-serif; color: #111827; font-size: 12px; display: flex; flex-direction: column; gap: 16px; border: 1px solid #e5e7eb; padding: 20px; border-radius: 6px; background-color: #fafafa; max-width: 800px;">
                            <!-- Председатель комиссии -->
                            <div>
                                <div style="font-weight: 800; text-transform: uppercase; font-size: 11px; color: #4b5563; margin-bottom: 6px; letter-spacing: 0.05em;">Председатель комиссии:</div>
                                <div style="display: grid; grid-template-columns: 280px 150px 1fr; align-items: center; gap: 16px;">
                                    <div>Начальник Учебного центра</div>
                                    <input type="text" name="commission_static[chairman_fio]" x-model="chairman" style="padding: 4px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 12px; font-weight: 600; width: 100%; max-width: 250px; height: 28px; background: #fff;">
                                </div>
                            </div>
                            <!-- Члены комиссии -->
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; display: grid; grid-template-columns: 280px 1fr; align-items: center; gap: 16px; min-height: 38px;">
                                <div style="font-weight: 800; text-transform: uppercase; font-size: 11px; color: #4b5563; letter-spacing: 0.05em;">Члены комиссии:</div>
                                <div style="display: flex; flex-wrap: wrap; gap: 14px; align-items: center;">
                                    <div style="font-size: 11px; color: #4b5563; font-style: italic;" 
                                         x-show="selectedMembers.length === 0 || selectedMembers.includes('-')">
                                        Члены комиссии не выбраны
                                    </div>
                                    <template x-for="(member, index) in selectedMembers" :key="index">
                                        <span x-show="member !== '-'" 
                                              style="font-size: 12px; font-weight: 600; color: #111827; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);" 
                                              x-text="member">
                                        </span>
                                    </template>
                                </div>
                            </div>
                            <!-- Исполнитель -->
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; display: grid; grid-template-columns: 280px 1fr; align-items: center; gap: 16px;">
                                <div style="font-weight: bold;">Исполнитель:</div>
                                <div style="display: flex; align-items: center;">
                                    <input type="hidden" name="commission[executor_fio]" value="{{ $report->specialist ?? auth()->user()->name ?? '-' }}">
                                    <span style="font-size: 12px; font-weight: 600; color: #111827; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); display: inline-block;">
                                        {{ $report->specialist ?? auth()->user()->name ?? '-' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                    @else
                        <h3 style="font-size: 15px; font-weight: 700; color: #111827;">Протокол заседания комиссии: {{ $learningTypeName }}</h3>
                        <p class="text-gray-500 text-sm mt-2">Спецификация и поля оценок для данного направления будут добавлены на следующих этапах.</p>
                    @endif
                </div>
                <div style="margin-top: 32px; padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
                    <input type="hidden" name="id_protocol" value="{{ $protocol->prot_id }}">
                    <a href="{{ route('protocols.index') }}" style="padding: 8px 20px; border: 1px solid #d1d5db; border-radius: 6px; background: #ffffff; font-weight: 500; text-decoration: none; color: #374151; font-size: 13px; display: inline-flex; align-items: center; h-[36px]" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='#ffffff'">Отмена</a>
                    <button type="submit" style="padding: 8px 24px; border: none; border-radius: 6px; background: #10b981; color: #ffffff; font-weight: 600; font-size: 13px; cursor: pointer; h-[36px]; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'">💾 Сохранить отчет</button>
                </div>
        </form>
    </div>
</x-layouts.app-with-sidebar>