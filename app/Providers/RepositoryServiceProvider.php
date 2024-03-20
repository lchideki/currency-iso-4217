<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ICurrencyRepository;
use App\Repositories\CurrencyRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        
    ];
}
