<?php

namespace App\Repositories;

interface ICurrencyRepository
{
    public function create(array $data);
    public function update(array $oldData, array $newData) ;
    public function findByCode(string $code);
}
