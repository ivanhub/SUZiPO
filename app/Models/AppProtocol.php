<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppProtocol extends Model
{
    protected $table = 'app_protocols';

    // Явно задаем первичный ключ (по умолчанию Laravel ищет просто "id")
    protected $primaryKey = 'prot_id';

    // Отключаем таймстампы, так как в таблице кастомные поля дат
    public $timestamps = false;

    /**
     * Связь с таблицей заявок (app_demands)
     */
    public function demand(): BelongsTo
    {
        // Внешний ключ в текущей таблице: dem_id
        // Первичный ключ в таблице app_demands: dem_id
        return $this->belongsTo(AppDemand::class, 'dem_id', 'dem_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(SecUser::class, 'id_user_edit', 'user_id');
    }
}
