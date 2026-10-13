<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReasonNonCertification extends Model
{
    protected $table = 'reasons_non_certification';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
