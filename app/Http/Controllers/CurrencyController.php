<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ICurrencyService;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(ICurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function find(Request $request)
    {
        $currencies = $this->currencyService->find(['code' => $request->get('code'), 
            'number' => $request->get('number'), 
            'number_list' => $request->get('number_list'), 
            'code_list' => $request->get('code_list')]);

        return response()->json($currencies);
    }
}
