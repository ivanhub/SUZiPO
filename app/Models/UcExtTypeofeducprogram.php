<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UcExtTypeofeducprogram extends Model
{
    protected $table = 'uc_ext_typeofeducprogram';
    protected $primaryKey = 'id_typeofeducprogram';

    protected $keyType = 'string'; // так как используется UUID, то тип ключа делам стрингом
    
    public $timestamps = false;
}
