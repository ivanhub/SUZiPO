<template x-teleport="body">
    <div x-show="isEditOpen"
         @click="isEditOpen = false"
         style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.55); z-index: 99999; overflow-y: auto; padding: 20px; box-sizing: border-box;"
         x-cloak>

        <div style="display: flex; align-items: center; justify-content: center; min-height: 100%;">
            <!-- Карточка модального окна (светлая и тёмная темы) -->
            <div @click.stop
                 style="background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 20px 35px rgba(0, 0, 0, 0.28); width: 100%; max-width: 1020px; max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column; font-size: 13px; color: var(--text-primary); box-sizing: border-box;">


        <!-- Заголовок окна -->
        <div style="padding: 16px 24px; background-color: var(--bg-surface); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 700; font-size: 16px; color: var(--text-primary);">Редактирование записи протокола</span>
                <span x-show="editData.prot_num" x-text="'#' + editData.prot_num"
                      style="padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; background-color: var(--pill-btn-bg); color: var(--text-primary);"></span>
            </div>
            <button type="button" @click="isEditOpen = false"
                    style="border: none; background: none; font-size: 24px; color: var(--text-secondary); cursor: pointer; line-height: 1; padding: 0;">&times;</button>
        </div>

        <!-- Тело формы -->
        <form :action="`/protocols/${editData.prot_id}`" method="POST" style="padding: 24px; margin: 0; box-sizing: border-box;">
            @csrf
            @method('PUT')

            <!-- Основной двухколоночный контейнер -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 32px; align-items: start;">

                <!-- ЛЕВАЯ КОЛОНКА: Данные из заявки -->
                <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px 20px; display: flex; flex-direction: column; gap: 11px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 2px;">
                        <span style="font-weight: 700; color: var(--text-secondary); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">Данные из заявки</span>

                        <label style="display: inline-flex; align-items: center; gap: 6px; margin: 0; cursor: not-allowed;">
                            <input type="checkbox" disabled id="is_disposable" :checked="editData.demand?.one_time == 1"
                                   style="margin: 0; width: 15px; height: 15px; accent-color: #EF4444;">
                            <span style="color: #EF4444; font-weight: 600; font-size: 12px;">Одноразовая заявка</span>
                        </label>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Дата начала курса:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="editData.demand?.start_date ? new Date(editData.demand.start_date).toLocaleDateString('ru-RU') : '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Дата окончания курса:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="editData.demand?.end_date ? new Date(editData.demand.end_date).toLocaleDateString('ru-RU') : '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Форма образования:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="editData.demand?.education_form || 'дистанционное'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: start; gap: 8px; padding: 6px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Учебное заведение:</span>
                        <span style="font-weight: 600; color: var(--text-primary); line-height: 1.35;" x-text="editData.demand?.provider?.name || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: start; gap: 8px; padding: 6px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Наименование ПО:</span>
                        <span style="font-weight: 600; color: var(--text-primary); line-height: 1.35;" x-text="editData.demand?.course?.course || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Страна / Город:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="(editData.demand?.country || '—') + ', ' + (editData.demand?.city?.city || '—')"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Аудитория:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="editData.demand?.audience?.number || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Преподаватель:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="editData.demand?.teacher?.fio || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 165px 1fr; align-items: center; gap: 8px; padding: 5px 10px; background-color: var(--bg-page); border-radius: 8px;">
                        <span style="color: var(--text-secondary); font-size: 12px;">Куратор группы:</span>
                        <span style="font-weight: 600; color: var(--text-primary);" x-text="editData.demand?.curator?.fio || '—'"></span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 4px;">
                        <span class="suzipo-label">Присваиваемая профессия</span>
                        <select name="assigned_profession_id" disabled class="suzipo-field" style="background-color: var(--bg-page) !important; opacity: 0.75; cursor: not-allowed;">
                            <option value="" x-text="editData.demand?.profession?.name || 'Не указана'"></option>
                        </select>
                    </div>
                </div>
                <!-- ПРАВАЯ КОЛОНКА: Реквизиты протокола -->
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="font-weight: 700; color: var(--text-secondary); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                        Реквизиты протокола
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="suzipo-label">Номер протокола <span style="color: #EF4444;">*</span></label>
                            <input type="text" name="prot_num" x-model="editData.prot_num" readonly
                                   class="suzipo-field" style="opacity: 0.7; cursor: not-allowed;">
                        </div>

                        <div>
                            <label class="suzipo-label">Дата формирования</label>
                            <input type="date" name="date_end" x-model="editData.date_end" class="suzipo-field">
                        </div>

                        <div>
                            <label class="suzipo-label">Номер группы <span style="color: #EF4444;">*</span></label>
                            <input type="text" name="group_num" x-model="editData.prot_num" required class="suzipo-field">
                        </div>

                        <div>
                            <label class="suzipo-label">Номер приказа <span style="color: #EF4444;">*</span></label>
                            <input type="text" name="order_num" x-model="editData.order_num" class="suzipo-field">
                        </div>

                        <div>
                            <label class="suzipo-label">Дата назначения приказа</label>
                            <input type="date" name="order_date" x-model="editData.order_date" class="suzipo-field">
                        </div>

                        <div>
                            <label class="suzipo-label">№/дата договора</label>
                            <select name="contract_id" class="suzipo-field">
                                <option value="">—</option>
                            </select>
                        </div>

                        <div>
                            <label class="suzipo-label">Цена за чел. (без НДС) <span style="color: #EF4444;">*</span></label>
                            <input type="text" name="Cost" x-model="editData.Cost" class="suzipo-field" style="text-align: right;">
                        </div>

                        <div>
                            <label class="suzipo-label">НДС %</label>
                            <input type="text" name="costvat" x-model="editData.costvat" class="suzipo-field" style="text-align: right;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                        <div>
                            <label class="suzipo-label">Часы теории <span style="color: #EF4444;">*</span></label>
                            <input type="number" name="theoryhours" x-model="editData.theoryhours" class="suzipo-field" style="text-align: right;">
                        </div>

                        <div>
                            <label class="suzipo-label">Часы практики <span style="color: #EF4444;">*</span></label>
                            <input type="number" name="practicehours" x-model="editData.practicehours" class="suzipo-field" style="text-align: right;">
                        </div>

                        <div>
                            <label class="suzipo-label">По программе <span style="color: #EF4444;">*</span></label>
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <input type="number" name="hoursbyprogram" readonly x-model="editData.hoursbyprogram"
                                       class="suzipo-field" style="opacity: 0.75; cursor: not-allowed; text-align: right;">
                                <button type="button" title="Рассчитать"
                                        style="height: 38px; padding: 0 10px; border: 1px solid var(--border-color); border-radius: 10px; background-color: var(--bg-surface); color: var(--text-primary); cursor: pointer; font-size: 13px; flex-shrink: 0;">📊</button>
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="suzipo-label">Тип документа</label>
                            <select name="document_type" x-model="editData.document_type" class="suzipo-field">
                                <option value="Сертификат">Сертификат</option>
                                <option value="Удостоверение">Удостоверение</option>
                            </select>
                        </div>

                        <div>
                            <label class="suzipo-label">Номер распоряжения <span style="color: #EF4444;">*</span></label>
                            <select name="disposition_id" class="suzipo-field">
                                <option value="" x-text="editData.order_num ? `(${editData.order_date || ''}) ${editData.order_num}` : '—'"></option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="flagapproved" :value="editData.prot_status || 1">
                </div>
            </div>

            <!-- Нижняя панель кнопок -->
            <div class="suzipo-actions" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color);">
                <button type="button" @click="isEditOpen = false" class="btn-suzipo-cancel">Отмена</button>
                <button type="submit" class="btn-suzipo-save">Сохранить изменения</button>
            </div>
        </form>
    </div>
</div>
</template>
