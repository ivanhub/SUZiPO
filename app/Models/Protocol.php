<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Protocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'protocol_number',
        'date',
        'status',
        'result',
        'file_path',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Связь с заявкой
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }
}