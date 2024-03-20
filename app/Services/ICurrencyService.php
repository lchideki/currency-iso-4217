<?php

namespace App\Services;

interface ICurrencyService
{
    public function createOrUpdate(array $data): void;
    public function find(array $filters): ?array;
}
