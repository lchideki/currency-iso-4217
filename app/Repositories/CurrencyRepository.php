<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository implements ICurrencyRepository
{
    protected $listKeyNames;

    public function __construct()
    {
        $this->listKeyNames = [
            'code_list' => 'code',
            'number_list' => 'number'
        ];
    }

    public function create(array $data): void
    {
        Currency::create($data);
    }

    public function update(array $oldData, array $newData): void
    {   
        Currency::where('_id', $oldData['_id'])->update($newData);
    }

    public function findByCode(string $code): ?array
    {
        $currency = Currency::where('code', '=', $code)->first();
        return $currency ? $currency->toArray() : null;
    }

    public function find(array $filters): ?array
    {
        $query = Currency::query();

        foreach ($filters as $key => $value)
        {
            if (gettype($value) == 'string')
                $query->where($key, $value);
           
            if (gettype($value) == 'array') 
                $query->whereIn($this->listKeyNames[$key], $value);
        }
        
        $currencies = $query->get();

        return $currencies->toArray();
    }
}
