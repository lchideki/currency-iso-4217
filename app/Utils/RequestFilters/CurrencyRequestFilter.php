<?php

namespace App\Utils\RequestFilters;
use Illuminate\Http\Request;
use InvalidArgumentException;

class CurrencyRequestFilter
{
    public static function configure(Request $request): array
    {
        $finalFilter = self::getFinalFilter($request);

        self::validateRequestFilterValue($finalFilter);

        return $finalFilter;
    }

    private static function allowedFilters() 
    {
        return ['code', 'code_list', 'number', 'number_list'];
    }

    private static function getFinalFilter(Request $request)
    {
        $finalFilter = [];
        $quantity_filter = 0;

        foreach (self::allowedFilters() as $key)
        {
            if ($request->has($key) && $request->input($key)) 
            {
                $requestFilter = $request->input($key);

                if (gettype($requestFilter) == 'array') 
                {
                    sort($requestFilter);
                    array_change_key_case($requestFilter, CASE_UPPER);                    
                }

                $finalFilter[$key] = $requestFilter;
                
                $quantity_filter++;

                if ($quantity_filter != 1)
                    throw new InvalidArgumentException("Informe exatamente um filtro para pesquisa.");
            }
        }

        return $finalFilter;
    }

    private static function validateRequestFilterValue(array $filter)
    {
        if (gettype($filter[array_key_first($filter)]) != 'array')
           return self::validateValue($filter[array_key_first($filter)]);

        foreach ($filter[array_key_first($filter)] as $value)
        {
            self::validateValue($value);            
        }
    }

    private static function validateValue($value) 
    { 
        self::validateNullOrEmpty($value);
        self::validateLength($value);
    }

    private static function validateNullOrEmpty($value)
    {
        if (empty($value) or $value == null)
            throw new InvalidArgumentException("O filtro não pode conter valor vazio ou nulo.");
    }

    private static function validateLength($value)
    {
        if (strlen($value) != 3)
            throw new InvalidArgumentException("O filtro não pode conter valores com tamanho diferente de 3.");
    }
}
