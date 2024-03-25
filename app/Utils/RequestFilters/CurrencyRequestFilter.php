<?php

namespace App\Utils\RequestFilters;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * Class CurrencyRequestFilter
 *
 * Classe para configurar e validar filtros de pesquisa de moeda.
 */
class CurrencyRequestFilter
{
    /**
     * Configura o filtro com base na requisição.
     *
     * @param Request $request A instância da requisição HTTP.
     * @return array O filtro configurado.
     * @throws InvalidArgumentException Se mais de um filtro for informado.
     */
    public static function configure(Request $request): array
    {
        $finalFilter = self::getFinalFilter($request);

        self::validateRequestFilterValue($finalFilter);

        return $finalFilter;
    }

    /**
     * Retorna os tipos de filtros permitidos.
     *
     * @return array Tipos de filtros permitidos.
     */
    private static function allowedFilters() 
    {
        return ['code', 'code_list', 'number', 'number_list'];
    }

    /**
     * Obtém o filtro final com base na requisição.
     *
     * @param Request $request A instância da requisição HTTP.
     * @return array O filtro final.
     * @throws InvalidArgumentException Se mais de um filtro for informado.
     */
    private static function getFinalFilter(Request $request)
    {
        $finalFilter = [];
        $quantity_filter = 0;

        foreach (self::allowedFilters() as $key)
        {
            if ($request->has($key)) 
            {
                $requestFilter = $request->input($key);

                if (gettype($requestFilter) == 'array') 
                {
                    sort($requestFilter);
                    array_change_key_case($requestFilter, CASE_UPPER);                    
                }

                $finalFilter[$key] = $requestFilter;
                
                $quantity_filter++;
            }
        }
    
        if ($quantity_filter != 1)
            throw new InvalidArgumentException("Informe exatamente um filtro para pesquisa.");

        return $finalFilter;
    }

    /**
     * Valida os valores do filtro.
     *
     * @param array $filter O filtro a ser validado.
     * @throws InvalidArgumentException Se o filtro for inválido.
     */
    private static function validateRequestFilterValue(array $filter)
    {
        if (gettype($filter[array_key_first($filter)]) != 'array')
           return self::validateValue($filter[array_key_first($filter)]);

        foreach ($filter[array_key_first($filter)] as $value)
        {
            self::validateValue($value);            
        }
    }

    /**
     * Valida um valor específico.
     *
     * @param mixed $value O valor a ser validado.
     * @throws InvalidArgumentException Se o valor for inválido.
     */
    private static function validateValue($value) 
    { 
        self::validateNullOrEmpty($value);
        self::validateLength($value);
    }

    /**
     * Valida se o valor é nulo ou vazio.
     *
     * @param mixed $value O valor a ser validado.
     * @throws InvalidArgumentException Se o valor for nulo ou vazio.
     */
    private static function validateNullOrEmpty($value)
    {
        if (empty($value) or $value == null)
            throw new InvalidArgumentException("O filtro não pode conter valor vazio ou nulo.");
    }

    /**
     * Valida o comprimento do valor.
     *
     * @param string $value O valor a ser validado.
     * @throws InvalidArgumentException Se o comprimento do valor for diferente de 3.
     */
    private static function validateLength($value)
    {
        if (strlen($value) != 3)
            throw new InvalidArgumentException("O filtro não pode conter valores com tamanho diferente de 3.");
    }
}
