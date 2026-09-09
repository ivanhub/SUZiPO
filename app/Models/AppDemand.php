<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppDemand extends Model
{
    protected $table = 'app_demands';
    protected $primaryKey = 'dem_id';
    public $timestamps = false;

    // Связь с курсом (таблица BK_Course)
    public function course(): BelongsTo { return $this->belongsTo(BkCourse::class, 'id_course', 'id'); }

    // Связь с Вузом (таблица BK_UZ)
    public function vuz(): BelongsTo { return $this->belongsTo(BkUz::class, 'id_vuz', 'id'); }

    // Связь с создателем заявки (Таблица users)
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'id_user_create', 'user_id'); }

    public function edType(): BelongsTo { return $this->belongsTo(BkSbiTypeEduc::class, 'idtypeeduc', 'id'); }
    public function form(): BelongsTo { return $this->belongsTo(BkForm::class, 'id_form', 'id'); }
    public function expenses(): BelongsTo { return $this->belongsTo(BkSbiDirectExpense::class, 'iddirectexpenses', 'id'); }
    public function typeDoc(): BelongsTo { return $this->belongsTo(BkTypeDoc::class, 'id_type_doc', 'id'); }

    public function editor(): BelongsTo { return $this->belongsTo(User::class, 'id_user_edit', 'user_id'); }
    public function country(): BelongsTo { return $this->belongsTo(BkCountry::class, 'id_country', 'id'); }
    public function city(): BelongsTo { return $this->belongsTo(BkCity::class, 'id_city', 'id'); }
    public function prof(): BelongsTo { return $this->belongsTo(BkProf::class, 'id_prof', 'id'); }
    public function division(): BelongsTo { return $this->belongsTo(RefScholDivision::class, 'id_schol_division', 'id'); }

}
