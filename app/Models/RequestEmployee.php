<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_sap_id',
        
        // Данные из SAP (сохраняем копию)
        'tab_number',
        'last_name',
        'first_name',
        'middle_name',
        'birth_date',
        'gender',
        'gender_key',
        'pfr_certificate',
        'position',
        'rank',
        'level_4_name',
        'level_3_name',
        'duv_b',
        'mvz',
        'employee_category',
        
        // Дополнительные поля
        'absence_start_date',
        'absence_end_date',
        'absence_reason', 
        'absence_type',
        'distance_learning_date',
        'fulltime_learning_date',
        'note',
        'document_issue_date',
        'reissue_period',
        'status',
        'warning_type',
        'warning_message'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'absence_start_date' => 'date',
        'absence_end_date' => 'date',
        'distance_learning_date' => 'date',
        'fulltime_learning_date' => 'date',
        'document_issue_date' => 'date',
    ];

    /**
     * Связь с заявкой
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Связь с сотрудником SAP
     */
    public function userSap(): BelongsTo
    {
        return $this->belongsTo(AllUserSap::class, 'user_sap_id');
    }

    /**
     * Получить ФИО сотрудника (из сохраненных данных)
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->last_name ?? '') . ' ' . ($this->first_name ?? '') . ' ' . ($this->middle_name ?? ''));
    }

    /**
     * Получить табельный номер (из сохраненных данных)
     */
    public function getTabNumberAttribute(): ?string
    {
        return $this->attributes['tab_number'] ?? null;
    }

    /**
     * Получить должность (из сохраненных данных)
     */
    public function getPositionAttribute(): ?string
    {
        return $this->attributes['position'] ?? null;
    }

    /**
     * Проверить просрочен ли документ (пункт 9)
     */
    public function isDocumentExpired(): bool
    {
        if (!$this->document_issue_date || !$this->reissue_period) {
            return false;
        }

        $expiryDate = $this->calculateExpiryDate();
        
        return $expiryDate && $expiryDate->lt(now());
    }

    /**
     * Рассчитать дату окончания документа
     */
    public function calculateExpiryDate(): ?\Carbon\Carbon
    {
        if (!$this->document_issue_date || !$this->reissue_period) {
            return null;
        }

        $issueDate = \Carbon\Carbon::parse($this->document_issue_date);
        
        switch ($this->reissue_period) {
            case '6 месяцев':
                $expiryDate = $issueDate->addMonths(6);
                break;
            case '1 год':
                $expiryDate = $issueDate->addYear();
                break;
            case '2 года':
                $expiryDate = $issueDate->addYears(2);
                break;
            case '3 года':
                $expiryDate = $issueDate->addYears(3);
                break;
            default:
                return null;
        }

        // Отнимаем 2 месяца
        return $expiryDate->subMonths(2);
    }
}