<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocolComission extends Model
{
    protected $table = 'protocol_comission';
    public $timestamps = false;
    protected $fillable = ['protocol_id', 'member_id', 'role'];

    public function member()
    {
        return $this->belongsTo(ComissionMember::class, 'member_id', 'id');
    }
}
