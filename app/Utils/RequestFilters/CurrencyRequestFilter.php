<?php

namespace App\Utils\RequestFilters;
use Illuminate\Http\Request;
use InvalidArgumentException;

class CurrencyRequestFilter
{
    public static function configure(Request $request): array
    {
        $allowed_filters = ['code', 'code_list', 'number', 'number_list'];
        $quantity_filter = 0;
        $real_filter = [];

        foreach ($allowed_filters as $key)
        {
            if ($request->has($key) && $request->input($key)) 
            {
                $requestFilter = $request->input($key);

                if (gettype($requestFilter) == 'array')
                    sort($requestFilter);

                $real_filter[$key] = $requestFilter;
                
                $quantity_filter++;

                if ($quantity_filter != 1)
                    throw new InvalidArgumentException("Informe exatamente um filtro para pesquisa.");
            }
        }
        
        return $real_filter;
    }
}
