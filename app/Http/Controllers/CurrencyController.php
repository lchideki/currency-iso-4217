<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Services\ICurrencyService;
use App\Utils\RequestFilters\CurrencyRequestFilter;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(ICurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

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
