<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'audience_id', 
        'teacher_id', 
        'start_date',
        'end_date', 
        'status', 
        'notes',
        'request_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class, 'audience_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
}