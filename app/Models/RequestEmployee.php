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
        'last_name',
        'first_name',
        'middle_name',
        'absence_start_date',
        'absence_end_date',
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
     * Получить ФИО сотрудника
     */
    public function getFullNameAttribute(): string
    {
        if ($this->userSap) {
            return $this->userSap->full_name ?? '';
        }
        
        return trim(($this->last_name ?? '') . ' ' . ($this->first_name ?? '') . ' ' . ($this->middle_name ?? ''));
    }

    /**
     * Получить табельный номер
     */
    public function getTabNumberAttribute(): ?string
    {
        return $this->userSap ? $this->userSap->tab_number : null;
    }

    /**
     * Получить должность
     */
    public function getPositionAttribute(): ?string
    {
        return $this->userSap ? $this->userSap->position : null;
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