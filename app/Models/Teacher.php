<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{

    protected $table = 'requests_teachers';
    protected $primaryKey = 'id';
    protected $fillable = ['fio', 'profession', 'division1', 'division2', 'division3'];
    public $timestamps = true;

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'teacher_id');
    }
}