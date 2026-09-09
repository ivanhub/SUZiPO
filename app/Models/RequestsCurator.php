<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestsCurator extends Model
{
    use HasFactory;

    protected $fillable = ['fio', 'profession', 'phone'];
}