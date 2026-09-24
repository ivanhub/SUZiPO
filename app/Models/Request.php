<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\HasActivity; 
use Spatie\Activitylog\Support\LogOptions;

class Request extends Model
{
    use HasFactory, HasActivity;

    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_URP_EDIT = 'urpedit';

    protected $table = 'requests'; 
    protected $fillable = [
        'req_id',
	'protection_requested', // для запроса снятия защиты
        'user_id',
        'status',
        'one_time',
        'start_date',
        'end_date',
        'issue_date',
        'education_form',
        'employee_type',
        'production_break',
        'provider_id',
        'course_id',
        'country',
        'city_id',
        'profession_id',
        'learn_reason_id',
        'learning_resource_id',
        'learning_type_id',
        'event_type_id',
        'discipline_id',
        'cost_profit',
        'audience_id',
        'teacher_id',
        'curator_id',
	    'reserve',
    ];

    protected $casts = [
        'one_time' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'issue_date' => 'date',
    ];

    /**
 * Маппинг полей на русские названия
 */
public static function getFieldLabels(): array
{
    return [
        'status' => 'Статус',
        'start_date' => 'Дата начала обучения',
        'end_date' => 'Дата окончания обучения',
        'issue_date' => 'Дата оформления',
        'course_id' => 'Курс',
        'provider_id' => 'Провайдер',
        'audience_id' => 'Аудитория',
        'teacher_id' => 'Преподаватель',
        'curator_id' => 'Куратор',
        'education_form' => 'Форма образования',
        'employee_type' => 'ИТР/рабочие',
        'production_break' => 'С отрывом от производства',
        'country' => 'Страна',
        'city_id' => 'Город',
        'profession_id' => 'Профессия',
        'cost_profit' => 'Себестоимость/Прибыль',
        'req_id' => 'Номер заявки',
    ];
}


public function getActivitylogOptions(): LogOptions
    {
            
    return LogOptions::defaults()
        ->logOnly([
            'start_date',
            'end_date',
            'issue_date',
            'course_id',
            'provider_id',
            'audience_id',
            'teacher_id',
            'curator_id',
        ])
        ->logOnlyDirty()
        ->dontLogEmptyChanges();

          // ->setDescriptionForEvent(fn(string $eventName) => "Заявка была {$eventName}");*/


/*
        return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty(); */
        
    }
/**
 * Форматирование значения поля для истории
 */
public static function formatFieldValue(string $field, mixed $value): string
{
    if ($value === null || $value === '') {
        return '—';
    }
    
    // Форматируем даты
    if (in_array($field, ['start_date', 'end_date', 'issue_date'])) {
        return \Carbon\Carbon::parse($value)->format('d.m.Y');
    }
    
    // Форматируем преподавателя
    if ($field === 'teacher_id') {
        $teacher = \App\Models\RequestsTeachers::find($value);
        return $teacher ? $teacher->fio : 'ID: ' . $value;
    }
    
    // Форматируем куратора
    if ($field === 'curator_id') {
        $curator = \App\Models\RequestsCurator::find($value);
        return $curator ? $curator->fio : 'ID: ' . $value;
    }
    
    // Форматируем курс
    if ($field === 'course_id') {
        $course = \App\Models\RequestsCourse::find($value);
        return $course ? $course->course : 'ID: ' . $value;
    }
    
    // Форматируем провайдера
    if ($field === 'provider_id') {
        $provider = \App\Models\RequestsProvider::find($value);
        return $provider ? $provider->name : 'ID: ' . $value;
    }
    
    // Форматируем аудиторию
    if ($field === 'audience_id') {
        $audience = \App\Models\RequestsAudience::find($value);
        return $audience ? $audience->number . ' (' . $audience->location . ')' : 'ID: ' . $value;
    }

    // Форматируем сотрудника
if ($field === 'employee_id') {
    $employee = \App\Models\RequestEmployee::find($value);
    return $employee ? $employee->full_name . ' (таб. №' . $employee->tab_number . ')' : 'ID: ' . $value;
}
    
    return (string)$value;
}

   public static function getStatuses(): array
    {
        return [
            self::STATUS_CREATED => 'Создана',
            self::STATUS_SENT => 'Отправлена',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_URP_EDIT => 'На доработке',
            self::STATUS_ACCEPTED => 'Принята',
            self::STATUS_REJECTED => 'Отклонена',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function protocols(): HasMany { return $this->hasMany(AppProtocol::class, 'prot_num', 'req_id'); }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function provider(): BelongsTo { return $this->belongsTo(RequestsProvider::class); }

    public function course(): BelongsTo { return $this->belongsTo(RequestsCourse::class, 'course_id'); }

    public function city(): BelongsTo { return $this->belongsTo(RequestsCity::class); }

    public function profession(): BelongsTo { return $this->belongsTo(RequestsProfession::class); }

    public function learnReason(): BelongsTo { return $this->belongsTo(RequestsLearnReason::class); }

    public function learningResource(): BelongsTo { return $this->belongsTo(RequestsLearningResource::class); }

    public function learningType(): BelongsTo { return $this->belongsTo(RequestsLearningType::class); }

    public function eventType(): BelongsTo { return $this->belongsTo(RequestsEventsType::class); }

    public function discipline(): BelongsTo { return $this->belongsTo(RequestsDiscipline::class); }

    public function audience(): BelongsTo { return $this->belongsTo(Audience::class, 'audience_id'); }

    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class, 'teacher_id'); }

    public function curator(): BelongsTo { return $this->belongsTo(RequestsCurator::class); }

    /**
     * Сотрудники заявки
     */
    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(RequestEmployee::class); }
 }
