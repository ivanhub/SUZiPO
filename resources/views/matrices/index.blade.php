<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;"
         x-data="{
            fileName: '',
            isUploading: false,
            progress: 0,
            statusText: '',
            stats: null,
            errorMessage: '',

            handleFileChange(e) {
                const file = e.target.files[0];
                this.fileName = file ? file.name : '';
                this.errorMessage = '';
                this.stats = null;
            },

            async uploadFile() {
                const fileInput = this.$refs.fileInput;
                if (!fileInput.files.length) {
                    this.errorMessage = 'Пожалуйста, выберите файл Excel (.xlsx) перед загрузкой.';
                    return;
                }

                this.isUploading = true;
                this.progress = 15;
                this.statusText = 'Загрузка файла на сервер...';
                this.errorMessage = '';
                this.stats = null;

                const formData = new FormData();
                formData.append('matrix_file', fileInput.files[0]);

                const timer = setInterval(() => {
                    if (this.progress < 88) {
                        this.progress += Math.floor(Math.random() * 8) + 3;
                        if (this.progress > 40) {
                            this.statusText = 'Чтение листов ПО, ДПО, Курсы, ОТ и обновление БД...';
                        }
                    }
                }, 350);

                try {
                    const response = await fetch('{{ route('matrices.import') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    clearInterval(timer);

                    if (!response.ok || !result.success) {
                        this.progress = 0;
                        this.errorMessage = result.message || 'Неизвестная ошибка сервера при импорте.';
                        return;
                    }

                    this.progress = 100;
                    this.statusText = 'Готово! Данные успешно синхронизированы с БД.';
                    this.stats = result.stats;
                } catch (err) {
                    clearInterval(timer);
                    this.progress = 0;
                    this.errorMessage = 'Сетевая ошибка при отправке файла: ' + err.message;
                } finally {
                    this.isUploading = false;
                }
            }
         }">

        <div class="suzipo-breadcrumbs">
            <a href="{{ route('dashboard') }}" class="suzipo-back-btn" title="На главную">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Матрицы программ обучения</span>
            </div>
        </div>

        <!-- Заголовок раздела -->
        <div style="margin-bottom: 24px;">
            <h1 class="suzipo-form-title" style="margin-bottom: 4px;">Матрицы программ обучения</h1>
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Выберите направление для просмотра реестра программ или обновите справочники из файла Excel</p>
        </div>
        <!-- Блок 1: Навигационные карточки по 4 матрицам -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 28px;">
            <!-- ПО -->
            <a href="{{ url('/matrices/po') }}" class="suzipo-card"
               style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px; text-decoration: none; border: 1px solid var(--border-color); transition: transform 0.12s ease, border-color 0.15s ease;"
               onmouseover="this.style.borderColor='#FFD100'; this.style.transform='translateY(-2px)';"
               onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='none';">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <span style="background: rgba(59, 130, 246, 0.14); color: #3B82F6; font-weight: 600; font-size: 12px; padding: 4px 10px; border-radius: 8px;">Матрица ПО</span>
                        <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600; padding: 2px 8px; border-radius: 9999px; background-color: var(--bg-page); border: 1px solid var(--border-color);">
                            {{ $counts['po'] }} прогр.
                        </span>
                    </div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Профессиональное обучение</div>
                    <div style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Подготовка, переподготовка и повышение квалификации рабочих</div>
                </div>
                <div style="margin-top: 16px; font-size: 12px; font-weight: 600; color: #D97706;">Открыть реестр →</div>
            </a>

            <!-- ДПО -->
            <a href="{{ url('/matrices/dpo') }}" class="suzipo-card"
               style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px; text-decoration: none; border: 1px solid var(--border-color); transition: transform 0.12s ease, border-color 0.15s ease;"
               onmouseover="this.style.borderColor='#FFD100'; this.style.transform='translateY(-2px)';"
               onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='none';">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <span style="background: rgba(139, 92, 246, 0.14); color: #8B5CF6; font-weight: 600; font-size: 12px; padding: 4px 10px; border-radius: 8px;">Матрица ДПО</span>
                        <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600; padding: 2px 8px; border-radius: 9999px; background-color: var(--bg-page); border: 1px solid var(--border-color);">
                            {{ $counts['dpo'] }} прогр.
                        </span>
                    </div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Дополнительное проф. образование</div>
                    <div style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Повышение квалификации специалистов и руководителей</div>
                </div>
                <div style="margin-top: 16px; font-size: 12px; font-weight: 600; color: #D97706;">Открыть реестр →</div>
            </a>

            <!-- Курсы -->
            <a href="{{ url('/matrices/courses') }}" class="suzipo-card"
               style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px; text-decoration: none; border: 1px solid var(--border-color); transition: transform 0.12s ease, border-color 0.15s ease;"
               onmouseover="this.style.borderColor='#FFD100'; this.style.transform='translateY(-2px)';"
               onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='none';">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <span style="background: rgba(16, 185, 129, 0.14); color: #10B981; font-weight: 600; font-size: 12px; padding: 4px 10px; border-radius: 8px;">Матрица Курсы</span>
                        <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600; padding: 2px 8px; border-radius: 9999px; background-color: var(--bg-page); border: 1px solid var(--border-color);">
                            {{ $counts['courses'] }} прогр.
                        </span>
                    </div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Курсовое обучение</div>
                    <div style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Дополнительные образовательные программы и спецкурсы</div>
                </div>
                <div style="margin-top: 16px; font-size: 12px; font-weight: 600; color: #D97706;">Открыть реестр →</div>
            </a>

            <!-- ОТ -->
            <a href="{{ url('/matrices/ot') }}" class="suzipo-card"
               style="display: flex; flex-direction: column; justify-content: space-between; padding: 20px; text-decoration: none; border: 1px solid var(--border-color); transition: transform 0.12s ease, border-color 0.15s ease;"
               onmouseover="this.style.borderColor='#FFD100'; this.style.transform='translateY(-2px)';"
               onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='none';">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <span style="background: rgba(245, 158, 11, 0.14); color: #D97706; font-weight: 600; font-size: 12px; padding: 4px 10px; border-radius: 8px;">Матрица ОТ</span>
                        <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600; padding: 2px 8px; border-radius: 9999px; background-color: var(--bg-page); border: 1px solid var(--border-color);">
                            {{ $counts['ot'] }} прогр.
                        </span>
                    </div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Охрана труда</div>
                    <div style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Программы ОТ, работы на высоте, первая помощь и ЕИСОТ</div>
                </div>
                <div style="margin-top: 16px; font-size: 12px; font-weight: 600; color: #D97706;">Открыть реестр →</div>
            </a>
        </div>
        <!-- Блок 2: Панель импорта файла Excel с прогресс-баром -->
        <div class="suzipo-card" style="padding: 24px; border: 1px solid var(--border-color);">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0 0 6px 0;">Обновление матриц из файла Excel</h2>
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0 0 20px 0;">
                Загрузите файл <strong style="color: var(--text-primary);">.xlsx</strong>, содержащий листы <code>ПО</code>, <code>ДПО</code>, <code>Курсы</code> и <code>ОТ</code>. Существующие записи будут обновлены автоматически.
            </p>

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background-color: var(--bg-page); border: 1px dashed var(--border-color); border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--text-primary); cursor: pointer;">
                    <span>📁 Выбрать файл Excel</span>
                    <input type="file" x-ref="fileInput" accept=".xlsx,.xls" @change="handleFileChange" style="display: none;" :disabled="isUploading">
                </label>

                <span x-show="fileName" x-text="fileName" x-cloak
                      style="font-size: 13px; color: var(--text-primary); font-weight: 500; background-color: var(--bg-page); border: 1px solid var(--border-color); padding: 7px 12px; border-radius: 10px;"></span>

                <button type="button" @click="uploadFile" :disabled="isUploading || !fileName"
                        class="btn-suzipo-save"
                        :style="(isUploading || !fileName) ? 'opacity: 0.45; cursor: not-allowed;' : 'opacity: 1;'">
                    <span x-show="!isUploading">⬆️ Загрузить в БД</span>
                    <span x-show="isUploading" x-cloak>Обработка файла...</span>
                </button>
            </div>

            <!-- Прогресс-бар -->
            <div x-show="progress > 0" x-cloak style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">
                    <span x-text="statusText"></span>
                    <span x-text="progress + '%'" style="color: var(--text-primary);"></span>
                </div>
                <div style="width: 100%; height: 10px; background-color: var(--bg-page); border-radius: 999px; overflow: hidden; border: 1px solid var(--border-color);">
                    <div style="height: 100%; background-color: #10B981; transition: width 0.3s ease;" :style="'width: ' + progress + '%'"></div>
                </div>
            </div>

            <!-- Блок ошибки -->
            <div x-show="errorMessage" x-cloak
                 style="margin-top: 16px; padding: 12px 16px; background-color: rgba(239, 68, 68, 0.12); border: 1px solid #EF4444; border-radius: 10px; color: #EF4444; font-size: 13px; font-weight: 500;">
                ⚠️ <span x-text="errorMessage"></span>
            </div>

            <!-- Карточки статистики после успешного импорта -->
            <div x-show="stats" x-cloak
                 style="margin-top: 20px; padding: 16px; background-color: rgba(16, 185, 129, 0.10); border: 1px solid #10B981; border-radius: 12px;">
                <div style="font-size: 14px; font-weight: 700; color: #10B981; margin-bottom: 12px;">✅ Результаты импорта по листам:</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                    <template x-for="(info, sheetName) in stats" :key="sheetName">
                        <div style="background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 10px; padding: 12px;">
                            <div style="font-weight: 700; font-size: 13px; color: var(--text-primary);" x-text="'Лист: ' + sheetName"></div>
                            <template x-if="info.status === 'ok'">
                                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 6px; line-height: 1.5;">
                                    Добавлено: <strong style="color: #10B981;" x-text="info.created"></strong><br>
                                    Обновлено: <strong style="color: #3B82F6;" x-text="info.updated"></strong>
                                </div>
                            </template>
                            <template x-if="info.status === 'not_found'">
                                <div style="font-size: 12px; color: #EF4444; margin-top: 6px;">Лист не найден в файле</div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app-with-sidebar>
