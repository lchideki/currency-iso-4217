<?php

namespace App\Models;



class Currency extends Model
{
    protected $collection = 'currency';

    protected $fillable = [
        'code',
        'number',
        'decimal_digits',
        'currency',
        'locations'
    ];
}
