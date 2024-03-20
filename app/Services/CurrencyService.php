<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Repositories\ICurrencyRepository;
use App\Services\ICrawlCurrencyService;
use App\Observers\CrawlCurrencyIso4217Observer;

class CurrencyService implements ICurrencyService
{
    protected $currencyRepository;
    protected $crawlCurrencyService;

    public function __construct(ICurrencyRepository $currencyRepository, ICrawlCurrencyService $crawlCurrencyService)
    {
        $this->currencyRepository = $currencyRepository;
        $this->crawlCurrencyService = $crawlCurrencyService;
    }

    // public function createOrUpdate(array $data): void
    // {   
    //     $currency = $this->currencyRepository->findByCode($data['code']);

    //     if ($currency == null)
    //         $this->currencyRepository->create($data);
    //     else
    //         $this->currencyRepository->update($currency, $data);
    // }

    public function find(array $filter): ?array
    {
        $keyFilter = json_encode($filter);

        $cachedResult = Cache::get($keyFilter);   

        return $cachedResult;
    }

    public function findCrawling(array $filter): ?array
    {
        $myCrawlObserver = new CrawlCurrencyIso4217Observer($this->crawlCurrencyService, $filter);

        $crawler = Crawler::create()
            ->setCrawlObserver($myCrawlObserver)
            ->setTotalCrawlLimit(1)
            ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');

        $result = $myCrawlObserver->getResult();

        return $result;
    }
}
