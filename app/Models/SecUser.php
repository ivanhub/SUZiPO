<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecUser extends Model
{
    protected $table = 'sec_users';
    protected $primaryKey = 'user_id';

    public $timestamps = false;
}
