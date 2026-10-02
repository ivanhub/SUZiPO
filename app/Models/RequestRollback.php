<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestRollback extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'subject_type',
        'subject_id',
        'activity_id',
        'user_id',
        'old_data',
        'new_data',
        'reason',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    /**
     * Связь с заявкой
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Связь с пользователем который сделал откат
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить тип изменения
     */
    public function getSubjectLabelAttribute(): string
    {
        return $this->subject_type === 'request' ? 'Заявка' : 'Сотрудник';
    }

    /**
     * Получить название измененного объекта
     */
    public function getSubjectNameAttribute(): string
    {
        if ($this->subject_type === 'request') {
            $request = Request::find($this->subject_id);
            return $request ? 'Заявка #' . $request->req_id : 'Заявка #' . $this->subject_id;
        }
        
        $employee = RequestEmployee::find($this->subject_id);
        return $employee ? $employee->full_name : 'Сотрудник #' . $this->subject_id;
    }
}