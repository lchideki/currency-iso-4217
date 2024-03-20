<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ICrawlCurrencyIso4217Observer;
use App\Observers\CrawlCurrencyIso4217Observer;

class ObserverServiceProvider extends ServiceProvider
{
     /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        ICrawlCurrencyIso4217Observer::class => CrawlCurrencyIso4217Observer::class,
    ];
}
