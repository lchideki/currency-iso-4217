<?php

namespace App\Repositories;

interface ICurrencyRepository
{
    public function create(array $data): void;
    public function update(array $oldData, array $newData): void;
    public function findByCode(string $code): ?array;
    public function find(array $filters): ?array;
}
