<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestHistory extends Model
{
    use HasFactory;

    protected $table = 'request_history';

    protected $fillable = [
        'request_id',
        'user_id',
        'action',
        'changes',
        'request_employee_id',
        'rollback_of',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(RequestEmployee::class, 'request_employee_id');
    }
}