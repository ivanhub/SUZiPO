<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class MatrixDpo extends Model
{
    use HasFactory;

    protected $table = 'matrix_dpo';

    protected $guarded = [];

    protected $casts = [
        'total_hours' => 'integer',
        'theoretical_hours' => 'integer',
        'self_study_hours' => 'integer',
        'industrial_practice_hours' => 'integer',
        'practical_hours' => 'integer',
    ];
}