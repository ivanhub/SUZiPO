<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_date',
        'within_year',
        'order_number',
        'updated_date',
        'updated_by',
    ];
}
