<?php

namespace App\Services;

interface ICurrencyService
{
    public function find(array $filter): ?array;
    public function findCrawling(array $filter): ?array;
}
