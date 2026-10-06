<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfEducation extends Model
{
    protected $table = 'type_of_education';
    public $timestamps = false; // В таблице нет временных полей created_at/updated_at

    protected $fillable = [
        'id',
        'name',
    ];
}
