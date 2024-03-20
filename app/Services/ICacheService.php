<?php

namespace App\Services;

interface ICacheService
{
    public function get($key);
    public function put($key, $value, $minutes);
}
