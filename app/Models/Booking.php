<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = ['audience_id', 'teacher_id', 'date', 'status', 'notes'];

    protected $casts = [
        'date' => 'date',
    ];

  public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class, 'audience_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}