<x-app-layout>
<div class="max-w-7xl mx-auto p-6" style="font-family: system-ui, -apple-system, sans-serif;"
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
                console.warn('Попытка импорта без выбранного файла');
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
                    console.error('Ошибка импорта матриц:', result);
                    return;
                }

                this.progress = 100;
                this.statusText = 'Готово! Данные успешно синхронизированы с БД.';
                this.stats = result.stats;
                console.info('Импорт матриц завершен:', result.stats);

            } catch (err) {
                clearInterval(timer);
                this.progress = 0;
                this.errorMessage = 'Сетевая ошибка при отправке файла: ' + err.message;
                console.error('Критическая ошибка fetch при импорте:', err);
            } finally {
                this.isUploading = false;
            }
        }
     }">
    <!-- Заголовок раздела -->
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">Матрицы программ обучения</h1>
        <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Выберите направление для просмотра реестра программ или обновите справочники из файла Excel</p>
    </div>

    <!-- Блок 1: Навигационные кнопки-карточки по 4 матрицам -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 32px;">
        
        <!-- ПО -->
        <a href="{{ url('/matrices/po') }}" style="display: block; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.03);"
           onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='none';">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="background: #eff6ff; color: #1d4ed8; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">Матрица ПО</span>
                <span style="font-size: 12px; color: #6b7280; font-weight: 600;">{{ $counts['po'] }} прогр.</span>
            </div>
            <div style="font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 4px;">Профессиональное обучение</div>
            <div style="font-size: 12px; color: #6b7280;">Подготовка, переподготовка и повышение квалификации рабочих</div>
        </a>

        <!-- ДПО -->
        <a href="{{ url('/matrices/dpo') }}" style="display: block; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.03);"
           onmouseover="this.style.borderColor='#8b5cf6'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='none';">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="background: #f5f3ff; color: #6d28d9; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">Матрица ДПО</span>
                <span style="font-size: 12px; color: #6b7280; font-weight: 600;">{{ $counts['dpo'] }} прогр.</span>
            </div>
            <div style="font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 4px;">Дополнительное проф. образование</div>
            <div style="font-size: 12px; color: #6b7280;">Повышение квалификации специалистов и руководителей</div>
        </a>

        <!-- Курсы -->
        <a href="{{ url('/matrices/courses') }}" style="display: block; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.03);"
           onmouseover="this.style.borderColor='#10b981'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='none';">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="background: #ecfdf5; color: #047857; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">Матрица Курсы</span>
                <span style="font-size: 12px; color: #6b7280; font-weight: 600;">{{ $counts['courses'] }} прогр.</span>
            </div>
            <div style="font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 4px;">Курсовое обучение</div>
            <div style="font-size: 12px; color: #6b7280;">Дополнительные образовательные программы и спецкурсы</div>
        </a>

        <!-- ОТ -->
        <a href="{{ url('/matrices/ot') }}" style="display: block; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.03);"
           onmouseover="this.style.borderColor='#f59e0b'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='none';">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="background: #fffbeb; color: #b45309; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">Матрица ОТ</span>
                <span style="font-size: 12px; color: #6b7280; font-weight: 600;">{{ $counts['ot'] }} прогр.</span>
            </div>
            <div style="font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 4px;">Охрана труда</div>
            <div style="font-size: 12px; color: #6b7280;">Программы ОТ, работы на высоте, первая помощь и ЕИСОТ</div>
        </a>

    </div>
    <!-- Блок 2: Панель импорта файла Excel с прогресс-баром -->
    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        <h2 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 6px 0;">Обновление матриц из файла Excel</h2>
        <p style="font-size: 13px; color: #6b7280; margin: 0 0 20px 0;">Загрузите файл <strong>.xlsx</strong>, содержащий листы <code>ПО</code>, <code>ДПО</code>, <code>Курсы</code> и <code>ОТ</code>. Существующие записи будут обновлены автоматически.</p>

        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
            <label style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #f9fafb; border: 1px dashed #9ca3af; border-radius: 6px; font-size: 13px; font-weight: 600; color: #374151; cursor: pointer;">
                <span>📁 Выбрать файл Excel</span>
                <input type="file" x-ref="fileInput" accept=".xlsx,.xls" @change="handleFileChange" style="display: none;" :disabled="isUploading">
            </label>

            <span x-show="fileName" x-text="fileName" style="font-size: 13px; color: #111827; font-weight: 500; background: #f3f4f6; padding: 6px 12px; border-radius: 6px;"></span>

            <button type="button" @click="uploadFile" :disabled="isUploading || !fileName"
                    style="padding: 9px 20px; background: #16a34a; color: #ffffff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                    :style="(isUploading || !fileName) ? 'opacity: 0.5; cursor: not-allowed;' : 'opacity: 1;'">
                <span x-show="!isUploading">⬆️ Загрузить</span>
                <span x-show="isUploading">Обработка файла...</span>
            </button>
        </div>

        <!-- Прогресс-бар -->
        <div x-show="progress > 0" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                <span x-text="statusText"></span>
                <span x-text="progress + '%'"></span>
            </div>
            <div style="width: 100%; height: 10px; background: #e5e7eb; border-radius: 999px; overflow: hidden;">
                <div style="height: 100%; background: #16a34a; transition: width 0.3s ease;" :style="'width: ' + progress + '%'"></div>
            </div>
        </div>

        <!-- Блок ошибки -->
        <div x-show="errorMessage" style="margin-top: 16px; padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #991b1b; font-size: 13px; font-weight: 500;">
            ⚠️ <span x-text="errorMessage"></span>
        </div>

        <!-- Карточки статистики после успешного импорта -->
        <div x-show="stats" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
            <div style="font-size: 14px; font-weight: 700; color: #166534; margin-bottom: 10px;">✅ Результаты импорта по листам:</div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                <template x-for="(info, sheetName) in stats" :key="sheetName">
                    <div style="background: #ffffff; border: 1px solid #dcfce7; border-radius: 6px; padding: 10px;">
                        <div style="font-weight: 700; font-size: 13px; color: #111827;" x-text="'Лист: ' + sheetName"></div>
                        <template x-if="info.status === 'ok'">
                            <div style="font-size: 12px; color: #374151; margin-top: 4px;">
                                Добавлено: <strong style="color: #16a34a;" x-text="info.created"></strong><br>
                                Обновлено: <strong style="color: #2563eb;" x-text="info.updated"></strong>
                            </div>
                        </template>
                        <template x-if="info.status === 'not_found'">
                            <div style="font-size: 12px; color: #dc2626; margin-top: 4px;">Лист не найден в файле</div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

