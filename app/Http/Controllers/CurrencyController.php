<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Services\ICurrencyService;
use App\Utils\RequestFilters\CurrencyRequestFilter;

/**
 * Class CurrencyController
 * 
 * Controlador para lidar com operações relacionadas a moedas.
 */
class CurrencyController extends Controller
{
    /**
     * O serviço de moeda a ser utilizado.
     *
     * @var ICurrencyService
     */
    protected $currencyService;

    /**
     * Cria uma nova instância do controlador de moeda.
     *
     * @param ICurrencyService $currencyService O serviço de moeda.
     */
    public function __construct(ICurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Encontra moedas com base nos filtros fornecidos na solicitação.
     *
     * @param Request $request A solicitação HTTP.
     * @return \Illuminate\Http\JsonResponse A resposta JSON contendo os dados das moedas encontradas.
     */
    public function find(Request $request)
    {
       
        try
        {
            $filter = CurrencyRequestFilter::configure($request);

            $cached = $this->currencyService->find($filter);
           
            if ($cached)
                return response()->json($cached);

            return response()->json($this->currencyService->findCrawling($filter));
        }
        catch (InvalidArgumentException $e)
        {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
