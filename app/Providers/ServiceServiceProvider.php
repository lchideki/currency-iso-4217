<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ICurrencyService;
use App\Services\CurrencyService;

class ServiceServiceProvider extends ServiceProvider
{
     /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        ICurrencyService::class => CurrencyService::class,
    ];
}
