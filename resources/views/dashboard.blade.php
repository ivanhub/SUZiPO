<x-layouts.app-with-sidebar>
    <div style="background-color: #0b0c0e; color: #ffffff; min-height: 100vh; padding: 24px; font-family: system-ui, -apple-system, sans-serif; box-sizing: border-box;">
        
        <!-- ВЕРХНИЙ КАПСУЛЬНЫЙ МЕНЮ-СЛАЙДЕР ИЗ МАКЕТА -->
        <div style="display: flex; justify-content: center; margin-bottom: 32px;">
            <div style="background-color: #131517; border: 1px solid #1c1f22; border-radius: 24px; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                <a href="#" style="background-color: #ffffff; color: #000000; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s;">Главная</a>
                <a href="{{ route('requests.index') }}" style="color: #8e969f; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8e969f'">Заявки</a>
                <a href="{{ route('protocols.index') }}" style="color: #8e969f; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8e969f'">Протоколы</a>
                <a href="{{ route('bookings.index') }}" style="color: #8e969f; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8e969f'">Бронирование</a>
                <a href="/directories/document-types" style="color: #8e969f; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8e969f'">Справочники</a>
                <a href="#" style="color: #8e969f; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8e969f'">Отчёты</a>
                <a href="#" style="color: #8e969f; padding: 8px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8e969f'">Ещё</a>
            </div>
        </div>

        <!-- БЛОК ПРИВЕТСТВИЯ И КНОПКИ -->
        <div style="margin-bottom: 32px;">
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('requests.create') }}" style="display: inline-flex; align-items: center; padding: 10px 22px; background-color: #ffffff; color: #000000; text-decoration: none; border-radius: 20px; font-size: 13px; font-weight: 600;">Создать заявку</a>
                <a href="{{ route('bookings.index') }}" style="display: inline-flex; align-items: center; padding: 10px 22px; background-color: #131517; color: #ffffff; text-decoration: none; border-radius: 20px; font-size: 13px; font-weight: 600; border: 1px solid #1c1f22;">Забронировать аудиторию</a>
                <a href="{{ route('protocols.create') }}" style="display: inline-flex; align-items: center; padding: 10px 22px; background-color: #131517; color: #ffffff; text-decoration: none; border-radius: 20px; font-size: 13px; font-weight: 600; border: 1px solid #1c1f22;">Новый протокол</a>
            </div>
        </div>

        <!-- ГЛАВНАЯ ДВУХКОЛОНОЧНАЯ СТРУКТУРА -->
        <div style="display: flex; gap: 24px; align-items: start; width: 100; box-sizing: border-box;">
            
            <!-- ЛЕВАЯ ЧАСТЬ (Инфо-карточки и дедлайны) -->
            <div style="width: 340px; min-width: 340px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div style="background-color: #131517; border: 1px solid #1c1f22; border-radius: 12px; padding: 16px; position: relative;">
                        <div style="font-size: 11px; color: #626971; font-weight: 500; margin-bottom: 4px;">Всего заявок</div>
                        <div style="display: flex; align-items: baseline; gap: 6px;">
                            <span style="font-size: 24px; font-weight: 700; color: #ffffff;">30</span>
                            <span style="font-size: 11px; color: #22c55e; font-weight: 600;">+6</span>
                        </div>
                        <div style="position: absolute; bottom: 8px; right: 12px; width: 45px; height: 15px; border-bottom: 2px solid #eab308; border-right: 2px solid #eab308; opacity: 0.4; border-radius: 0 0 4px 0;"></div>
                    </div>
                    <div style="background-color: #131517; border: 1px solid #1c1f22; border-radius: 12px; padding: 16px; position: relative;">
                        <div style="font-size: 11px; color: #626971; font-weight: 500; margin-bottom: 4px;">Протоколов</div>
                        <div style="display: flex; align-items: baseline; gap: 6px;">
                            <span style="font-size: 24px; font-weight: 700; color: #ffffff;">40</span>
                            <span style="font-size: 11px; color: #ef4444; font-weight: 600;">-5</span>
                        </div>
                        <div style="position: absolute; bottom: 8px; right: 12px; width: 45px; height: 15px; border-bottom: 2px solid #626971; border-right: 2px solid #626971; opacity: 0.3; border-radius: 0 0 4px 0;"></div>
                    </div>
                    <div style="background-color: #131517; border: 1px solid #1c1f22; border-radius: 12px; padding: 16px; position: relative;">
                        <div style="font-size: 11px; color: #626971; font-weight: 500; margin-bottom: 4px;">Ожидают обработки</div>
                        <div style="display: flex; align-items: baseline; gap: 6px;">
                            <span style="font-size: 24px; font-weight: 700; color: #ffffff;">16</span>
                            <span style="font-size: 11px; color: #eab308; font-weight: 600;">+3</span>
                        </div>
                        <div style="position: absolute; bottom: 8px; right: 12px; width: 45px; height: 15px; border-bottom: 2px solid #eab308; border-right: 2px solid #eab308; opacity: 0.4; border-radius: 0 0 4px 0;"></div>
                    </div>
                    <div style="background-color: #131517; border: 1px solid #1c1f22; border-radius: 12px; padding: 16px; position: relative;">
                        <div style="font-size: 11px; color: #626971; font-weight: 500; margin-bottom: 4px;">Обучено сотрудников</div>
                        <div style="display: flex; align-items: baseline; gap: 6px;">
                            <span style="font-size: 24px; font-weight: 700; color: #ffffff;">22</span>
                            <span style="font-size: 11px; color: #ef4444; font-weight: 600;">-4</span>
                        </div>
                        <div style="position: absolute; bottom: 8px; right: 12px; width: 45px; height: 15px; border-bottom: 2px solid #626971; border-right: 2px solid #626971; opacity: 0.3; border-radius: 0 0 4px 0;"></div>
                    </div>
                </div>

                <div style="background-color: #131517; border: 1px solid #1c1f22; border-radius: 16px; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 13px; font-weight: 700; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Нужно разобрать</h3>
                        <span style="font-size: 13px; font-weight: 700; color: #626971;">7</span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="font-size: 13px;">
                            <div style="display: flex; justify-content: space-between; font-weight: 600; color: #ffffff; margin-bottom: 4px;">
                                <span>Крылова Светлана Олеговна</span>
                                <span style="color: #ef4444; font-size: 11px; font-weight: 500;">просрочено 4 дн.</span>
                            </div>
                            <div style="color: #8e969f; font-size: 12px;">Химическая безопасность • <span style="color: #eab308; font-weight: 600;">● Нет скана</span></div>
                        </div>
                        <div style="font-size: 13px; border-top: 1px solid #1c1f22; padding-top: 12px;">
                            <div style="display: flex; justify-content: space-between; font-weight: 600; color: #ffffff; margin-bottom: 4px;">
                                <span>Широкова Елена Юрьевна</span>
                                <span style="color: #ef4444; font-size: 11px; font-weight: 500;">просрочено 3 дн.</span>
                            </div>
                            <div style="color: #8e969f; font-size: 12px;">Химическая безопасность • <span style="color: #eab308; font-weight: 600;">● Ждёт согласования</span></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ПРАВАЯ ЧАСТЬ (Основной реестр "Последние заявки") -->
                        <!-- ПРАВАЯ ЧАСТЬ (Основной динамический реестр) -->
            <div style="flex-grow: 1; background-color: #131517; border: 1px solid #1c1f22; border-radius: 16px; padding: 24px; box-sizing: border-box;" x-data="{ searchQuery: '' }">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #1c1f22; padding-bottom: 14px;">
                    <h2 style="font-size: 16px; font-weight: 700; color: #ffffff; margin: 0;">Последние заявки в работе</h2>
                    <a href="{{ route('protocols.index') }}" style="color: #626971; text-decoration: none; font-size: 12px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#626971'">Все протоколы →</a>
                </div>

                <!-- Строка аккуратного быстрого поиска по базе -->
                <div style="margin-bottom: 20px;">
                    <input type="text" x-model="searchQuery" placeholder="Поиск по номеру группы или курсу..." 
                           style="width: 100%; max-width: 320px; background-color: #0b0c0e; border: 1px solid #1c1f22; border-radius: 12px; padding: 8px 14px; font-size: 13px; color: #ffffff; outline: none; box-sizing: border-box;">
                </div>

                <!-- ТАБЛИЧНАЯ СЕТКА С РЕАЛЬНЫМИ ДАННЫМИ ИЗ ВАШЕЙ БД -->
                <div style="overflow-x: auto; width: 100%;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                        <thead>
                            <tr style="color: #626971; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; height: 36px; border-bottom: 1px solid #1c1f22;">
                                <th style="padding: 10px 12px; width: 90px; text-align: center;">ID Группы</th>
                                <th style="padding: 10px 12px;">Программа обучения (Курс)</th>
                                <th style="padding: 10px 12px; width: 180px;">Статус протокола</th>
                                <th style="padding: 10px 12px; width: 120px; text-align: center;">Дата док-та</th>
                            </tr>
                        </thead>
                        <tbody style="color: #cdd5de;">
                            @forelse($recentRequests ?? $recentProtocols ?? [] as $request)
                            @php
                                // Определяем ФИО курса и номер группы из привязанных связей, которые мы делали
                                $courseName = $request->demand?->course?->course ?? $request->course_name ?? '—';
                                $groupNum = $request->prot_num ?? $request->id ?? '—';
                                $statusValue = $request->prot_status ?? $request->status ?? 'Черновик';
                            @endphp
                            <tr style="border-bottom: 1px solid #1c1f22; height: 44px;" 
                                onmouseover="this.style.backgroundColor='#1a1d20'" 
                                onmouseout="this.style.backgroundColor='transparent'"
                                x-show="searchQuery === '' || 
                                        '{{ mb_strtolower($groupNum) }}'.includes(searchQuery.toLowerCase()) || 
                                        '{{ mb_strtolower($courseName) }}'.includes(searchQuery.toLowerCase())">
                                <td style="padding: 10px 12px; text-align: center; color: #ffffff; font-weight: 700; font-family: monospace;">{{ $groupNum }}</td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #ffffff;">{{ $courseName }}</td>
                                <td style="padding: 10px 12px; white-space: nowrap; font-weight: 700;">
                                    <span style="display: inline-flex; align-items: center; gap: 6px;
                                        @if($statusValue == '0' || $statusValue === 'Черновик' || $statusValue === 'draft') color: #626971;
                                        @elseif($statusValue == '1' || $statusValue === 'Ждёт согласования' || $statusValue === 'sent') color: #eab308;
                                        @elseif($statusValue == '2' || $statusValue === 'Занятия назначено' || $statusValue === 'accepted') color: #3b82f6;
                                        @else color: #22c55e;
                                        @endif">
                                        ● <span style="font-size: 12px; font-weight: 600;">
                                            @if($statusValue == '0' || $statusValue === 'draft') Черновик 
                                            @elseif($statusValue == '1' || $statusValue === 'sent') Ждёт согласования
                                            @elseif($statusValue == '2' || $statusValue === 'accepted') Занятия назначено
                                            @else {{ $statusValue }} @endif
                                        </span>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; text-align: center; color: #626971;">
                                    {{ $request->prot_date ? \Carbon\Carbon::parse($request->prot_date)->format('d.m.Y') : ($request->created_at ? $request->created_at->format('d.m.Y') : '—') }}
                                </td>
                            </tr>
                            @empty
                            <!-- НАДЕЖНЫЙ ФАЛЛБЭК ИЗ МАКЕТА, ЕСЛИ ВЫДАЧА КОНТРОЛЛЕРА ЕЩЕ НЕ ПЕРЕДАНА -->
                            <tr style="border-bottom: 1px solid #1c1f22; height: 44px;">
                                <td style="padding: 10px 12px; text-align: center; color: #ffffff; font-weight:700; font-family: monospace;">3-30623</td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #ffffff;">Стропальщик (Программа повышения квалификации)</td>
                                <td style="padding: 10px 12px; color: #3b82f6; font-weight:700;">● <span style="font-weight:600;">Занятия назначено</span></td>
                                <td style="padding: 10px 12px; text-align: center; color: #626971;">15.10.2026</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #1c1f22; height: 44px;">
                                <td style="padding: 10px 12px; text-align: center; color: #ffffff; font-weight:700; font-family: monospace;">3-30622</td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #ffffff;">Охрана труда при работе на высоте II группа</td>
                                <td style="padding: 10px 12px; color: #eab308; font-weight:700;">● <span style="font-weight:600;">Ждёт согласования</span></td>
                                <td style="padding: 10px 12px; text-align: center; color: #626971;">12.10.2026</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app-with-sidebar>

