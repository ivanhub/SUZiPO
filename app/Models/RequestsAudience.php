<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestsAudience extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'location', 'responsible_person', 'seats'];
}