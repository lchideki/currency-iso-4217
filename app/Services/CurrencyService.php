<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Repositories\ICurrencyRepository;
use App\Services\ICrawlCurrencyService;
use App\Observers\CrawlCurrencyIso4217Observer;

class CurrencyService implements ICurrencyService
{
    protected $crawlCurrencyService;
    private $dataFiltred;
    private $cacheTimingInSeconds;

    public function __construct(ICrawlCurrencyService $crawlCurrencyService)
    {        
        $this->crawlCurrencyService = $crawlCurrencyService;
        $this->dataFiltred = [];
        $this->cacheTimingInMinutes = 720;
    }
    
    public function find(array $filter): ?array
    {
        $cachedResult = Cache::get($this->getKeyFilter($filter));   

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
        Cache::put($this->getKeyFilter($filter), $this->dataFiltred, now()->addMinutes($this->cacheTimingInMinutes));
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
