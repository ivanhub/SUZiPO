<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UcExtReport extends Model
{
    protected $table = 'uc_ext_report';
    protected $primaryKey = 'id_report';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_report',
        'id_protocol',
        'specialist',
        'numbergroup',
        'countworker',
        'countleader',
        'countspecialist',
        'Show',
        'noshow',
        'passed',
        'nopassed',
        'fulltime',
        'distance',
        'countwoman',
        'countman',
        'countung',
        'countservise',
        'countothercomp',
        'countcash',
        'teacher1',
        'specialistuc',
        'curatorurp',
        'audiencenumber',
    ];
}
