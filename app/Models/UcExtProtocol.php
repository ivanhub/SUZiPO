<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UcExtProtocol extends Model
{
    protected $table = 'uc_ext_protocol';
    protected $primaryKey = 'id_protocol'; // Первичный ключ с учетом регистра
    public $timestamps = false;

    // Связь со справочником курсов (BK_Course)
    public function course(): BelongsTo
    {
        return $this->belongsTo(BkCourse::class, 'prot_id_course', 'id');
    }

    public function demand(): BelongsTo
    {
        return $this->belongsTo(AppDemand::class, 'dem_id', 'dem_id');
    }

    // Связь с создателем / пользователем (users)
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Связь с редактором (users)
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_edit', 'user_id');
    }

    // Связь с учебным отделом (Ref_Schol_Division)
    public function division(): BelongsTo
    {
        return $this->belongsTo(RefScholDivision::class, 'id_schol_division', 'id');
    }

    // Связь с причиной не прохождения аттестации (Ref_Reason)
    public function reason(): BelongsTo
    {
        return $this->belongsTo(RefReason::class, 'id_reason', 'id');
    }

    public function program(): BelongsTo
    {
        // 2 аргумент - внешний ключ в текущей таблице, 3 аргумент - ключ в целевой таблице
        return $this->belongsTo(UcExtTypeofeducprogram::class, 'id_typeofeducprogram', 'id_typeofeducprogram');
    }
}
