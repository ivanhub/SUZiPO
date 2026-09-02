<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    protected $casts = [
        'one_time' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'issue_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(RequestsProvider::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(RequestsCourse::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(RequestsCity::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(RequestsProfession::class);
    }

    public function learnReason(): BelongsTo
    {
        return $this->belongsTo(RequestsLearnReason::class);
    }

    public function learningResource(): BelongsTo
    {
        return $this->belongsTo(RequestsLearningResource::class);
    }

    public function learningType(): BelongsTo
    {
        return $this->belongsTo(RequestsLearningType::class);
    }

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(RequestsEventsType::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(RequestsDiscipline::class);
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(RequestsAudience::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(RequestsTeachers::class);
    }

    public function curator(): BelongsTo
    {
        return $this->belongsTo(RequestsCurator::class);
    }
}