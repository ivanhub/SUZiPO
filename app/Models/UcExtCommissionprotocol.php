<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UcExtCommissionprotocol extends Model
{
    protected $table = 'uc_ext_commissionprotocol';
    public $timestamps = false;
    
    protected $primaryKey = 'id_compr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_compr', 'id_listcommission', 'id_protocol', 'fio', 'staff', 'note', 'sortorder'
    ];
}
