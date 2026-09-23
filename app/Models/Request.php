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


public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status',
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
            ->dontLogEmptyChanges() 
            ->setDescriptionForEvent(fn(string $eventName) => "Заявка была {$eventName}");
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
