<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrixDpo extends Model
{
    use HasFactory;

    protected $table = 'matrix_dpo';

    protected $fillable = [
        'row_number', 'code', 'dpo_type', 'program_name', 'full_name',
        'study_form', 'total_hours', 'theoretical_hours', 'self_study_hours',
        'industrial_practice_hours', 'practical_hours', 'student_category',
        'group_capacity', 'attestation_form', 'commission_type', 'issued_document',
        'note', 'uchi_pro', 'information_system_entry', 'teacher_requirements',
        'equipment', 'equipment_location', 'teacher_name'
    ];

    protected $casts = [
        'total_hours' => 'integer',
        'theoretical_hours' => 'integer',
        'self_study_hours' => 'integer',
        'industrial_practice_hours' => 'integer',
        'practical_hours' => 'integer',
    ];
}