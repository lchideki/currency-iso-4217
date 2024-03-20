<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currency';

    protected $fillable = [
        'code',
        'number',
        'decimal_digits',
        'name'
    ];
}
