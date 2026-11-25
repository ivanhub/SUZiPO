<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;" x-data="{ addTab: 'single' }">
        <!-- Хлебные крошки и круглая кнопка Назад -->
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('requests.show', $trainingRequest->id) }}" class="suzipo-back-btn" title="Назад к заявке">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('requests.index') }}" style="color: var(--text-secondary); text-decoration: none;">Заявки на обучение</a>
                <span>›</span>
                <a href="{{ route('requests.show', $trainingRequest->id) }}" style="color: var(--text-secondary); text-decoration: none;">Заявка #{{ $trainingRequest->id }}</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Сотрудники</span>
            </div>
        </div>

        <!-- Уведомления -->
        @if(session('success'))
            <div style="padding: 12px 16px; background-color: rgba(16, 185, 129, 0.12); border: 1px solid #10B981; color: #10B981; border-radius: 10px; font-size: 13px; margin-bottom: 18px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 12px 16px; background-color: rgba(239, 68, 68, 0.12); border: 1px solid #EF4444; color: #EF4444; border-radius: 10px; font-size: 13px; margin-bottom: 18px;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- Заголовок страницы -->
        <div style="margin-bottom: 20px;">
            <h1 class="suzipo-form-title" style="margin-bottom: 4px;">Сотрудники заявки #{{ $trainingRequest->id }}</h1>
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                Программа обучения: <strong style="color: var(--text-primary);">{{ $trainingRequest->course->course ?? 'Не указана' }}</strong>
            </p>
        </div>

        <!-- Карточка добавления сотрудников с переключением режимов -->
        <div class="suzipo-card" style="padding: 18px 20px; margin-bottom: 24px; border: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 16px; flex-wrap: wrap;">
                <span @click="addTab = 'single'" class="suzipo-tab-item" :class="{ 'active': addTab === 'single' }">
                    По табельному номеру
                </span>
                <span @click="addTab = 'bulk'" class="suzipo-tab-item" :class="{ 'active': addTab === 'bulk' }">
                    Пакетное добавление (список)
                </span>
                <span @click="addTab = 'manual'" class="suzipo-tab-item" :class="{ 'active': addTab === 'manual' }">
                    Ручной ввод ФИО
                </span>
            </div>

            <!-- Вкладка 1: По табельному номеру -->
            <div x-show="addTab === 'single'">
                <form action="{{ route('request-employees.store', $trainingRequest->id) }}" method="POST" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;">
                    @csrf
                    <div style="flex: 1; min-width: 240px; max-width: 380px;">
                        <label for="tab_number" class="suzipo-label">Табельный номер (SAP)</label>
                        <input type="text" name="tab_number" id="tab_number" value="{{ old('tab_number') }}"
                               class="suzipo-field" style="background-color: var(--bg-page) !important;"
                               placeholder="Например: 10000066">
                        @error('tab_number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-suzipo-save">
                        + Добавить сотрудника
                    </button>
                </form>
            </div>

            <!-- Вкладка 2: Пакетное добавление -->
            <div x-show="addTab === 'bulk'" x-cloak>
                <form action="{{ route('request-employees.store-bulk', $trainingRequest->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <label for="tab_numbers" class="suzipo-label">Табельные номера (каждый с новой строки)</label>
                        <textarea name="tab_numbers" id="tab_numbers" rows="5"
                                  class="suzipo-field" style="background-color: var(--bg-page) !important; font-family: monospace;"
                                  placeholder="10000066&#10;10000069&#10;10000071"></textarea>
                    </div>
                    <button type="submit" class="btn-suzipo-save">
                        + Добавить списком
                    </button>
                </form>
            </div>

            <!-- Вкладка 3: Ручной ввод сотрудника -->
            <div x-show="addTab === 'manual'" x-cloak>
                <form action="{{ route('request-employees.store', $trainingRequest->id) }}" method="POST">
                    @csrf
                    <div class="suzipo-grid-6" style="margin-bottom: 14px;">
                        <div class="suzipo-col-2">
                            <label for="manual_last_name" class="suzipo-label">Фамилия *</label>
                            <input type="text" name="last_name" id="manual_last_name" value="{{ old('last_name') }}"
                                   class="suzipo-field" style="background-color: var(--bg-page) !important;" required>
                        </div>
                        <div class="suzipo-col-2">
                            <label for="manual_first_name" class="suzipo-label">Имя *</label>
                            <input type="text" name="first_name" id="manual_first_name" value="{{ old('first_name') }}"
                                   class="suzipo-field" style="background-color: var(--bg-page) !important;" required>
                        </div>
                        <div class="suzipo-col-2">
                            <label for="manual_middle_name" class="suzipo-label">Отчество</label>
                            <input type="text" name="middle_name" id="manual_middle_name" value="{{ old('middle_name') }}"
                                   class="suzipo-field" style="background-color: var(--bg-page) !important;">
                        </div>
                    </div>
                    <button type="submit" class="btn-suzipo-save">
                        + Добавить вручную
                    </button>
                </form>
            </div>
        </div>
        <!-- Двухслойная таблица сотрудников заявки (Figma style) -->
        <div class="suzipo-table-shell" style="overflow-x: auto;">
            @php
                $empColGroup = '
                <colgroup>
                    <col style="width: 44px;">
                    <col style="width: 85px;">
                    <col style="width: 190px;">
                    <col style="width: 90px;">
                    <col style="width: 55px;">
                    <col style="width: 110px;">
                    <col style="width: 165px;">
                    <col style="width: 65px;">
                    <col style="width: 155px;">
                    <col style="width: 155px;">
                    <col style="width: 75px;">
                    <col style="width: 90px;">
                    <col style="width: 100px;">
                    <col style="width: 90px;">
                    <col style="width: 90px;">
                    <col style="width: 95px;">
                    <col style="width: 90px;">
                    <col style="width: 90px;">
                    <col style="width: 84px;">
                </colgroup>';
            @endphp

            <!-- 1. Шапка таблицы на сером фоне -->
            <table class="suzipo-table-head" style="min-width: 1920px;">
                {!! $empColGroup !!}
                <thead>
                    <tr>
                        <th style="text-align: center;"></th>
                        <th>Таб. №</th>
                        <th>ФИО</th>
                        <th>Дата рожд.</th>
                        <th>Пол</th>
                        <th>Свид. ПФР</th>
                        <th>Должность</th>
                        <th>Разряд</th>
                        <th>Уровень 4</th>
                        <th>Уровень 3</th>
                        <th>ДУвБ</th>
                        <th>МВЗ</th>
                        <th>Категория</th>
                        <th>Дата нач.</th>
                        <th>Дата окон.</th>
                        <th>Форма</th>
                        <th>Дата док.</th>
                        <th>Периодичн.</th>
                        <th style="text-align: right; padding-right: 12px;">Действия</th>
                    </tr>
                </thead>
            </table>

            <!-- 2. Внутренний скруглённый блок строк -->
            <div class="suzipo-table-body-box" style="min-width: 1920px;">
                <table class="suzipo-table-body" style="min-width: 1920px;">
                    {!! $empColGroup !!}
                    <tbody>
                        @forelse($employees as $employee)
                            @php
                                $isAlertRow = in_array($employee->status, ['blocked', 'expired', 'dismissed']) || !$employee->user_sap_id;
                                $isWarningRow = $employee->status === 'warning';
                                $rowBg = $isAlertRow
                                    ? 'rgba(239, 68, 68, 0.10)'
                                    : ($isWarningRow ? 'rgba(245, 158, 11, 0.10)' : '');
                            @endphp
                            <tr style="{{ $rowBg ? 'background-color: ' . $rowBg . ';' : '' }}">
                                <!-- Быстрая кнопка редактирования слева -->
                                <td style="text-align: center;">
                                    <button type="button"
                                            class="suzipo-action-circle"
                                            style="width: 28px; height: 28px; min-width: 28px;"
                                            title="Редактировать"
                                            data-id="{{ $employee->id }}"
                                            data-request-id="{{ $trainingRequest->id }}"
                                            data-start="{{ $employee->absence_start_date ? $employee->absence_start_date->format('Y-m-d') : '' }}"
                                            data-end="{{ $employee->absence_end_date ? $employee->absence_end_date->format('Y-m-d') : '' }}"
                                            data-absence-reason="{{ $employee->absence_reason ?? '' }}"
                                            data-type="{{ $employee->absence_type }}"
                                            data-distance="{{ $employee->distance_learning_date ? $employee->distance_learning_date->format('Y-m-d') : '' }}"
                                            data-fulltime="{{ $employee->fulltime_learning_date ? $employee->fulltime_learning_date->format('Y-m-d') : '' }}"
                                            data-note="{{ $employee->note }}"
                                            data-doc-date="{{ $employee->document_issue_date ? $employee->document_issue_date->format('Y-m-d') : '' }}"
                                            data-period="{{ $employee->reissue_period }}"
                                            onclick="openEditModal(this)">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>
                                </td>

                                <td style="font-weight: 500;">{{ $employee->tab_number ?? '—' }}</td>

                                <td style="font-weight: 500;" title="{{ $employee->full_name }}">
                                    {{ $employee->full_name ?? '—' }}
                                    @if(!$employee->user_sap_id)
                                        <span style="margin-left: 4px; padding: 1px 6px; background-color: rgba(239, 68, 68, 0.15); color: #EF4444; border-radius: 6px; font-size: 10px; font-weight: 700;">нет в SAP</span>
                                    @endif
                                    @if($employee->warning_message)
                                        <div style="font-size: 11px; color: #EF4444; margin-top: 2px;">{{ $employee->warning_message }}</div>
                                    @endif
                                </td>

                                <td style="color: var(--table-head-text);">{{ $employee->birth_date ? $employee->birth_date->format('d.m.Y') : '—' }}</td>

                                <td style="color: var(--table-head-text);">
                                    @if($employee->gender == '1') муж. @elseif($employee->gender == '2') жен. @else — @endif
                                </td>

                                <td style="color: var(--table-head-text);" title="{{ $employee->pfr_certificate }}">{{ $employee->pfr_certificate ?? '—' }}</td>
                                <td style="color: var(--table-head-text);" title="{{ $employee->position }}">{{ $employee->position ?? '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->rank ?? '—' }}</td>
                                <td style="color: var(--table-head-text);" title="{{ $employee->level_4_name }}">{{ $employee->level_4_name ?? '—' }}</td>
                                <td style="color: var(--table-head-text);" title="{{ $employee->level_3_name }}">{{ $employee->level_3_name ?? '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->duv_b ?? '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->mvz ?? '—' }}</td>
                                <td style="color: var(--table-head-text);" title="{{ $employee->employee_category }}">{{ $employee->employee_category ?? '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->absence_start_date ? $employee->absence_start_date->format('d.m.Y') : '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->absence_end_date ? $employee->absence_end_date->format('d.m.Y') : '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->absence_type ?? '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->document_issue_date ? $employee->document_issue_date->format('d.m.Y') : '—' }}</td>
                                <td style="color: var(--table-head-text);">{{ $employee->reissue_period ?? '—' }}</td>

                                <!-- Действия справа -->
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; gap: 4px;">
                                        <button type="button"
                                                class="suzipo-action-circle"
                                                title="Редактировать"
                                                data-id="{{ $employee->id }}"
                                                data-request-id="{{ $trainingRequest->id }}"
                                                data-start="{{ $employee->absence_start_date ? $employee->absence_start_date->format('Y-m-d') : '' }}"
                                                data-end="{{ $employee->absence_end_date ? $employee->absence_end_date->format('Y-m-d') : '' }}"
                                                data-absence-reason="{{ $employee->absence_reason ?? '' }}"
                                                data-type="{{ $employee->absence_type }}"
                                                data-distance="{{ $employee->distance_learning_date ? $employee->distance_learning_date->format('Y-m-d') : '' }}"
                                                data-fulltime="{{ $employee->fulltime_learning_date ? $employee->fulltime_learning_date->format('Y-m-d') : '' }}"
                                                data-note="{{ $employee->note }}"
                                                data-doc-date="{{ $employee->document_issue_date ? $employee->document_issue_date->format('Y-m-d') : '' }}"
                                                data-period="{{ $employee->reissue_period }}"
                                                onclick="openEditModal(this)">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </button>

                                        <button type="button"
                                                class="suzipo-action-circle"
                                                style="background-color: rgba(239, 68, 68, 0.14); color: #EF4444;"
                                                title="Удалить"
                                                data-id="{{ $employee->id }}"
                                                data-request-id="{{ $trainingRequest->id }}"
                                                onclick="deleteEmployee(this)">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="19" style="padding: 28px; text-align: center; color: var(--table-head-text); font-size: 13px;">
                                    Сотрудники не добавлены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Модальное окно редактирования сотрудника (светлая и тёмная темы) -->
    <div id="editModal" style="display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.55); padding: 16px;" onclick="if(event.target === this) closeEditModal()">
        <div style="background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 16px; max-width: 540px; width: 100%; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25); color: var(--text-primary);">
            <!-- Шапка модального окна -->
            <div style="padding: 16px 20px; background-color: var(--bg-surface); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0; color: var(--text-primary);">Редактирование сотрудника</h3>
                <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 22px; color: var(--text-secondary); cursor: pointer; line-height: 1; padding: 0;">&times;</button>
            </div>

            <form id="editForm" method="POST" style="padding: 20px;">
                @csrf
                @method('PUT')

                <div class="suzipo-grid-6">
                    <div class="suzipo-col-3">
                        <label for="edit_absence_start_date" class="suzipo-label">Дата начала отсутствия</label>
                        <input type="date" name="absence_start_date" id="edit_absence_start_date" class="suzipo-field">
                    </div>

                    <div class="suzipo-col-3">
                        <label for="edit_absence_end_date" class="suzipo-label">Дата окончания отсутствия</label>
                        <input type="date" name="absence_end_date" id="edit_absence_end_date" class="suzipo-field">
                    </div>

                    <div class="suzipo-col-6">
                        <label for="edit_absence_reason" class="suzipo-label">Причина отсутствия</label>
                        <textarea name="absence_reason" id="edit_absence_reason" rows="2" class="suzipo-field"></textarea>
                    </div>

                    <div class="suzipo-col-3">
                        <label for="edit_absence_type" class="suzipo-label">Форма обучения</label>
                        <select name="absence_type" id="edit_absence_type" class="suzipo-field" onchange="toggleDistanceFields()">
                            <option value="">Выберите форму</option>
                            <option value="очно">Очно</option>
                            <option value="заочно">Заочно</option>
                            <option value="очно-заочное">Очно-заочное</option>
                        </select>
                    </div>

                    <div class="suzipo-col-3">
                        <label for="edit_document_issue_date" class="suzipo-label">Дата выдачи документа</label>
                        <input type="date" name="document_issue_date" id="edit_document_issue_date" class="suzipo-field">
                    </div>

                    <!-- Поля для очно-заочной формы -->
                    <div id="distance_fields" class="suzipo-col-6" style="display: none; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label for="edit_distance_learning_date" class="suzipo-label">Дата заочного обучения</label>
                            <input type="date" name="distance_learning_date" id="edit_distance_learning_date" class="suzipo-field">
                        </div>

                        <div>
                            <label for="edit_fulltime_learning_date" class="suzipo-label">Дата очного обучения</label>
                            <input type="date" name="fulltime_learning_date" id="edit_fulltime_learning_date" class="suzipo-field">
                        </div>
                    </div>

                    <div class="suzipo-col-6">
                        <label for="edit_reissue_period" class="suzipo-label">Периодичность обучения</label>
                        <select name="reissue_period" id="edit_reissue_period" class="suzipo-field">
                            <option value="">Выберите периодичность</option>
                            <option value="6 месяцев">6 месяцев</option>
                            <option value="1 год">1 год</option>
                            <option value="2 года">2 года</option>
                            <option value="3 года">3 года</option>
                        </select>
                    </div>

                    <div class="suzipo-col-6">
                        <label for="edit_note" class="suzipo-label">Примечание</label>
                        <textarea name="note" id="edit_note" rows="2" class="suzipo-field"></textarea>
                    </div>
                </div>

                <div class="suzipo-actions" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color);">
                    <button type="button" onclick="closeEditModal()" class="btn-suzipo-cancel">Отмена</button>
                    <button type="submit" class="btn-suzipo-save">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const employeeId = button.dataset.id;
            const requestId = button.dataset.requestId;
            const form = document.getElementById('editForm');

            form.action = `/request-employees/${requestId}/${employeeId}`;

            document.getElementById('edit_absence_start_date').value = button.dataset.start || '';
            document.getElementById('edit_absence_end_date').value = button.dataset.end || '';
            document.getElementById('edit_absence_reason').value = button.dataset.absenceReason || '';
            document.getElementById('edit_absence_type').value = button.dataset.type || '';
            document.getElementById('edit_distance_learning_date').value = button.dataset.distance || '';
            document.getElementById('edit_fulltime_learning_date').value = button.dataset.fulltime || '';
            document.getElementById('edit_document_issue_date').value = button.dataset.docDate || '';
            document.getElementById('edit_reissue_period').value = button.dataset.period || '';
            document.getElementById('edit_note').value = button.dataset.note || '';

            toggleDistanceFields();
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function toggleDistanceFields() {
            const type = document.getElementById('edit_absence_type').value;
            const distanceFields = document.getElementById('distance_fields');
            const distanceDate = document.getElementById('edit_distance_learning_date');
            const fulltimeDate = document.getElementById('edit_fulltime_learning_date');

            if (type === 'очно-заочное') {
                distanceFields.style.display = 'grid';
                distanceDate.disabled = false;
                fulltimeDate.disabled = false;
            } else {
                distanceFields.style.display = 'none';
                distanceDate.disabled = true;
                fulltimeDate.disabled = true;
            }
        }

        function deleteEmployee(button) {
            if (!confirm('Удалить сотрудника из заявки?')) return;

            const employeeId = button.dataset.id;
            const requestId = button.dataset.requestId;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/request-employees/${requestId}/${employeeId}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleDistanceFields();
            @if(session('success'))
                const tabNumbersInput = document.getElementById('tab_numbers');
                if (tabNumbersInput) tabNumbersInput.value = '';
            @endif
        });
    </script>
</x-layouts.app-with-sidebar>
