<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppProtocol extends Model
{
    protected $table = 'app_protocols';
    protected $primaryKey = 'prot_id';
    public $timestamps = false;
    protected $with = ['demand'];

    protected $fillable = [
        'prot_id',        
        'prot_num',       
        'prot_status',    
        'prot_date',      
        'id_user_create', 
        'date_edit',
        'id_user_edit',
        'date_start',
        'date_end',
        'row_version', 
    ];

    protected static function booted()
    {
        static::creating(function ($protocol) {
            $maxId = static::max('prot_id');
            $protocol->prot_id = $maxId ? $maxId + 1 : 1;
        });
    }


    public function demand(): BelongsTo
    {
        return $this->belongsTo(Request::class, 'prot_num', 'req_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(SecUser::class, 'id_user_edit', 'user_id');
    }
}
