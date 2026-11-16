<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class MatrixPo extends Model
{
    use HasFactory;

    protected $table = 'matrix_po';

    protected $guarded = [];

    protected $casts = [
        'hours' => 'integer',
        'theory_hours' => 'integer',
        'self_study_hours' => 'integer',
        'practical_hours' => 'integer',
        'practice_hours' => 'integer',
    ];
}