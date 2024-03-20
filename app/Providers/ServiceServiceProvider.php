<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ICurrencyService;
use App\Services\CurrencyService;
use App\Services\ICrawlCurrencyService;
use App\Services\CrawlCurrencyService;
use App\Services\ICacheService;
use App\Services\CacheService;

class ServiceServiceProvider extends ServiceProvider
{
     /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        ICurrencyService::class => CurrencyService::class,
        ICrawlCurrencyService::class => CrawlCurrencyService::class,
        ICacheService::class => CacheService::class,
    ];
}
