<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class MatrixOt extends Model
{
    use HasFactory;

    protected $table = 'matrix_ot';

    protected $guarded = [];

    protected $casts = [
        'total_hours' => 'integer',
        'fulltime_theoretical_hours' => 'float',
        'distance_theoretical_hours' => 'float',
        'industrial_practice_hours' => 'integer',
        'practical_hours' => 'float',
    ];
}