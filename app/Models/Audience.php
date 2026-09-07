<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audience extends Model
{
    protected $table = 'requests_audiences';
    protected $fillable = ['number', 'location', 'responsible_person', 'seats'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'audience_id');
    }
}