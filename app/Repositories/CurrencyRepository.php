<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository implements ICurrencyRepository
{
    public function create(array $data) 
    {
        Currency::create($data);
    }

    public function update(array $oldData, array $newData) 
    {   
        Currency::where('_id', $oldData['_id'])->update($newData);
    }

    public function findByCode(string $code) 
    {
        $currency = Currency::where('code', '=', $code)->first();
        return $currency ? $currency->toArray() : null;
    }
}
