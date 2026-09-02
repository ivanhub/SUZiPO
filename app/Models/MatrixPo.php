<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrixPo extends Model
{
    use HasFactory;

    protected $table = 'matrix_po';

    protected $fillable = [
        'number', 'code', 'education_type', 'profession_name', 'rank',
        'full_name', 'study_form', 'hours', 'theory_hours', 'self_study_hours',
        'practical_hours', 'practice_hours', 'listener_category', 'group_size',
        'control_form', 'commission_type', 'document_type', 'notes', 'uchipro',
        'info_system', 'teacher_requirements', 'equipment', 'equipment_location',
        'teacher_fio'
    ];

    protected $casts = [
        'hours' => 'integer',
        'theory_hours' => 'integer',
        'self_study_hours' => 'integer',
        'practical_hours' => 'integer',
        'practice_hours' => 'integer',
    ];
}