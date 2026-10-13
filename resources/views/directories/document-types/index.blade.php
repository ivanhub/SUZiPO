<x-layouts.app-with-sidebar>
    <div class="max-w-full mx-auto p-6" style="font-family: system-ui, -apple-system, sans-serif; background-color: #fafafa; min-height: 100vh;"
        x-data="{ 
            isAddOpen: false, 
            isEditOpen: false, 
            editId: '', 
            editName: '', 
            editSapCode: '',
            openEdit(id, name, sapCode) {
                this.editId = id;
                this.editName = name;
                this.editSapCode = sapCode;
                this.isEditOpen = true;
            }
         }">

        <!-- Верхняя шапка и кнопка Добавить -->
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">Типы документов</h1>
                <p style="font-size: 13px; color: #6b7280; margin: 0;">Справочник видов выдаваемых документов</p>
            </div>
            <button type="button" @click="isAddOpen = true" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background-color: #3b82f6; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; color: #ffffff; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                ➕ Добавить тип
            </button>
        </div>

        <!-- Системные уведомления об успехе -->
        @if(session('success'))
        <div style="padding: 10px 16px; background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; border-radius: 6px; font-size: 13px; margin-bottom: 16px; font-weight: 500;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- Табличный блок реестра -->
        <div style="overflow-x: auto; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); box-sizing: border-box;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; color: #374151; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; height: 40px;">
                        <th width="80" style="padding: 10px 16px; text-align: center;">ID</th>
                        <th style="padding: 10px 16px;">Наименование типа документа</th>
                        <th width="150" style="padding: 10px 16px; text-align: center;">Код SAP</th>
                        <th width="120" style="padding: 10px 16px; text-align: center;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentTypes as $type)
                    <tr style="border-bottom: 1px solid #e5e7eb; height: 44px;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='#ffffff'">
                        <td style="padding: 10px 16px; text-align: center; color: #6b7280; font-weight: 500;">{{ $type->id }}</td>
                        <td style="padding: 10px 16px; font-weight: 600; color: #111827;">{{ $type->name }}</td>
                        <td style="padding: 10px 16px; text-align: center; font-family: monospace; color: #4b5563;">{{ $type->sap_code ?? '—' }}</td>
                        <td style="padding: 10px 16px; text-align: center; white-space: nowrap;">
                            <span @click="openEdit({{ $type->id }}, '{{ $type->name }}', '{{ $type->sap_code }}')" style="cursor: pointer; color: #4f46e5; font-size: 14px; margin-right: 14px;" title="Редактировать">✏️</span>

                            <form action="/directories/document-types/{{ $type->id }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Вы уверены, что хотите удалить этот тип документа?');">

                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; cursor: pointer; color: #ef4444; font-size: 14px; padding: 0;" title="Удалить">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 24px; text-align: center; color: #9ca3af;">Справочник пуст. Нажмите кнопку «Добавить тип» для наполнения.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- МОДАЛЬНОЕ ОКНО: ДОБАВЛЕНИЕ-->
        <div x-show="isAddOpen" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.3); z-index: 99999;" x-cloak>
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #ffffff; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); width: 100%; max-width: 420px; border: 1px solid #e5e7eb; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
                <div style="padding: 14px 20px; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center;">
                    <span style="font-weight: 700; font-size: 14px; color: #111827;">Добавить новый тип документа</span>
                    <button type="button" @click="isAddOpen = false" style="margin-left: auto; border: none; background: none; font-size: 20px; color: #9ca3af; cursor: pointer;">&times;</button>
                </div>
                <form action="{{ route('directories.document-types.store') }}" method="POST" style="padding: 20px; display: flex; flex-direction: column; gap: 12px; margin: 0; box-sizing: border-box;">
                    @csrf
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 4px;">Наименование: *</label>
                        <input type="text" name="name" required placeholder="Например: Сертификат" style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 4px;">Код SAP:</label>
                        <input type="text" name="sap_code" placeholder="Например: 41 (необязательно)" style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>

                    <div style="margin-top: 8px; padding-top: 12px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="button" @click="isAddOpen = false" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px; background: #fff; cursor: pointer; font-size:12px;">Отмена</button>
                        <button type="submit" style="padding: 6px 16px; border: none; border-radius: 4px; background: #3b82f6; color: #fff; font-weight:600; cursor: pointer; font-size:12px;">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- МОДАЛЬНОЕ ОКНО: РЕДАКТИРОВАНИЕ-->
        <div x-show="isEditOpen" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.3); z-index: 99999;" x-cloak>
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #ffffff; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); width: 100%; max-width: 420px; border: 1px solid #e5e7eb; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
                <div style="padding: 14px 20px; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center;">
                    <span style="font-weight: 700; font-size: 14px; color: #111827;">Редактировать тип документа</span>
                    <button type="button" @click="isEditOpen = false" style="margin-left: auto; border: none; background: none; font-size: 20px; color: #9ca3af; cursor: pointer;">&times;</button>
                </div>
                <form :action="`/directories/document-types/${editId}/update`" method="POST" style="padding: 20px; display: flex; flex-direction: column; gap: 12px; margin: 0; box-sizing: border-box;">

                    @csrf
                    @method('PUT')
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 4px;">Наименование: *</label>
                        <input type="text" name="name" x-model="editName" required style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 4px;">Код SAP:</label>
                        <input type="text" name="sap_code" x-model="editSapCode" style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>
                    <div style="margin-top: 8px; padding-top: 12px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="button" @click="isEditOpen = false" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px; background: #fff; cursor: pointer; font-size:12px;">Отмена</button>
                        <button type="submit" style="padding: 6px 16px; border: none; border-radius: 4px; background: #4f46e5; color: #fff; font-weight:600; cursor: pointer; font-size:12px;">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.app-with-sidebar>