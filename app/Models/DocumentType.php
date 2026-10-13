<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'document_types';
    public $timestamps = false;

    protected $fillable = [
        'valid_from',
        'valid_to',
        'sap_code',
        'name',
        'print_code',
        'changed_at',
        'changed_by',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'changed_at' => 'datetime',
    ];
}
