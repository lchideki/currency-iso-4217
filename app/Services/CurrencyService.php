<?php

namespace App\Services;

use App\Repositories\ICurrencyRepository;

class CurrencyService implements ICurrencyService
{
    protected $currencyRepository;

    public function __construct(ICurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function create(array $data)
    {
        return $this->currencyRepository->create($data);
    }
}
