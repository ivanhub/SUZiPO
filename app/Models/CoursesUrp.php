<?php
// app/Models/CoursesUrp.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class CoursesUrp extends Model
{
    use HasFactory;

    protected $table = 'courses_urp';

    protected $fillable = [
        'code',
        'name',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
    ];
}