<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository implements ICurrencyRepository
{
    public function create(array $data) 
    {
        return Currency::create($data);
    }
}
