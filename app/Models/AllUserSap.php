<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllUserSap extends Model
{
    use HasFactory;

    protected $table = 'all_users_sap';

    protected $fillable = [
        'tab_number', 'last_name', 'first_name', 'middle_name', 'birth_date',
        'gender', 'gender_key', 'pfr_certificate', 'position', 'rank',
        'level_4_name', 'level_3_name', 'duv_b', 'mvz', 'employee_category'
    ];

    protected $casts = [
        'tab_number' => 'integer',
        'birth_date' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name);
    }
}