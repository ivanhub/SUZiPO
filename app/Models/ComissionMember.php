<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissionMember extends Model
{
    protected $table = 'comission_members';
    public $timestamps = false;
    protected $fillable = ['fio', 'position'];
}
