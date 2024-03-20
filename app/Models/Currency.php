<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Currency extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'currency';

    protected $fillable = [
        'code',
        'number',
        'decimal_digits',
        'currency',
        'locations'
    ];
}
