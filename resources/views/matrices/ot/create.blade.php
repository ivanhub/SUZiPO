<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('matrices.ot.index') }}" class="suzipo-back-btn" title="Назад к списку ОТ">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('matrices.ot.index') }}" style="color: var(--text-secondary); text-decoration: none;">Охрана труда</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Добавление программы ОТ</span>
            </div>
        </div>

        <h1 class="suzipo-form-title">Добавление новой программы ОТ</h1>

        @if(session('error'))
            <div style="margin-bottom: 16px; padding: 12px 16px; background-color: rgba(239, 68, 68, 0.1); border: 1px solid #EF4444; color: #EF4444; border-radius: 10px; font-size: 13px;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('matrices.ot.store') }}" method="POST">
            @csrf

            @include('matrices.ot._form')

            <div class="suzipo-actions">
                <a href="{{ route('matrices.ot.index') }}" class="btn-suzipo-cancel">Отмена</a>
                <button type="submit" class="btn-suzipo-save">Создать</button>
            </div>
        </form>
    </div>

    @if(session('error') || $errors->any())
        <script>
            console.error('Ошибка создания ОТ:', { sessionError: @json(session('error')), validationErrors: @json($errors->all()) });
        </script>
    @endif
</x-layouts.app-with-sidebar>
