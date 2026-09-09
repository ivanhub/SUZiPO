<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrixOt extends Model
{
    use HasFactory;

    protected $table = 'matrix_ot';

    protected $fillable = [
        'row_number', 'code', 'training_type', 'program_name', 'full_name',
        'study_form', 'total_hours', 'fulltime_theoretical_hours',
        'distance_theoretical_hours', 'industrial_practice_hours',
        'practical_hours', 'student_category', 'group_capacity', 'control_form',
        'commission_type', 'issued_document', 'note', 'uchi_pro',
        'information_system_entry', 'equipment', 'equipment_location',
        'teacher_name', 'code_ucjung', 'code_ul'
    ];

    protected $casts = [
        'total_hours' => 'integer',
        'fulltime_theoretical_hours' => 'float',
        'distance_theoretical_hours' => 'float',
        'industrial_practice_hours' => 'integer',
        'practical_hours' => 'float',
    ];
}