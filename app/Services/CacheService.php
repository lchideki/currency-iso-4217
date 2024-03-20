<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Services\ICacheService;

class CacheService implements ICacheService
{
    public function get($key)
    {
        return Cache::get($key);   
    }

    public function put($key, $value, $timing)
    {
        Cache::put($key, $value, $timing); 
    }
}
