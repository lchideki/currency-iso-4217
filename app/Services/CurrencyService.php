<?php

namespace App\Services;

use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Repositories\ICurrencyRepository;
use App\Services\ICrawlCurrencyService;
use App\Observers\ICrawlCurrencyIso4217Observer;
use App\Services\ICacheService;

class CurrencyService implements ICurrencyService
{
    protected $crawlCurrencyService;
    protected $cacheService;
    protected $crawlCurrencyIso4217Observer;
    protected $dataFiltred;
    protected $cacheTimingInMinutes;

    public function __construct(ICrawlCurrencyService $crawlCurrencyService, ICacheService $cacheService, ICrawlCurrencyIso4217Observer $crawlCurrencyIso4217Observer)
    {        
        $this->crawlCurrencyService = $crawlCurrencyService;
        $this->crawlCurrencyIso4217Observer = $crawlCurrencyIso4217Observer;
        $this->cacheService = $cacheService;
        $this->dataFiltred = [];
        $this->cacheTimingInMinutes = 720;
    }
    
    public function find(array $filter): ?array
    {
        $cachedResult = $this->cacheService->get($this->getKeyFilter($filter));

        return $cachedResult;
    }

    public function findCrawling(array $filter): ?array
    {
        $this->crawlCurrencyIso4217Observer->setFilter($filter);

        $crawler = Crawler::create()
            ->setCrawlObserver($this->crawlCurrencyIso4217Observer)
            ->setTotalCrawlLimit(1)
            ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');

        $result = $this->crawlCurrencyIso4217Observer->getResult();

        $this->createOrUpdate($filter, $result);

        return $this->dataFiltred;
    }

    private function createOrUpdate(array $filter, array $data): array
    {   
        $nameFilter = array_key_first($filter);
        $keyFind = ($nameFilter == 'number_list' or $nameFilter == 'number') ? 'number' : 'code';

        if (gettype($filter[$nameFilter]) == 'array')
        {
            foreach ($filter[$nameFilter] as $filterValue) 
            {
                $this->tryAddDataItem($data, $keyFind, $filterValue);
            }
        }
        else 
        {
            $this->tryAddDataItem($data, $keyFind, $filter[$nameFilter]);
        }


        $this->storeCache($filter);

        return $this->dataFiltred;
    }

    private function getKeyFilter(array $filter): string
    {
        return json_encode($filter);
    }

    private function storeCache(array $filter): void 
    {
        $this->cacheService->put($this->getKeyFilter($filter), $this->dataFiltred, now()->addMinutes($this->cacheTimingInMinutes));
    }

    private function tryAddDataItem(array $data, string $keyFind, string $filterValue): void
    {
        $mapedData = $this->mapData($data, $keyFind, $filterValue);
        if ($mapedData)
            $this->dataFiltred[] = $mapedData;
    }

    private function mapData(array $data, string $keyFind, string $filterValue)
    {
        $dataByFilter = array_filter($data, function ($value) use (&$keyFind, &$filterValue) {
            
            return strtoupper($value[$keyFind]) == strtoupper($filterValue);
        });

       return $dataByFilter[array_key_first($dataByFilter)];
    }
}
