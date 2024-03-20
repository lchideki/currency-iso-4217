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

    public function createOrUpdate(array $data): void
    {   
        $currency = $this->currencyRepository->findByCode($data['code']);

        if ($currency == null)
            $this->currencyRepository->create($data);
        else
            $this->currencyRepository->update($currency, $data);
    }

    public function find(array $filters): ?array
    {
        return  $this->currencyRepository->find($filters);
    }
}
