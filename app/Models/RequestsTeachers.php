<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestsTeachers extends Model
{
    use HasFactory;

    protected $fillable = ['fio', 'profession', 'division1', 'division2', 'division3'];

    protected $table = 'requests_teachers';
}