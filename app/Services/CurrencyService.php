<?php

namespace App\Services;

use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Repositories\ICurrencyRepository;
use App\Services\ICrawlCurrencyService;
use App\Observers\ICrawlCurrencyIso4217Observer;
use App\Services\ICacheService;

/**
 * Class CurrencyService
 * 
 * Serviço para manipulação de moedas, incluindo busca e rastreamento.
 */
class CurrencyService implements ICurrencyService
{
    /** @var ICrawlCurrencyService O serviço de rastreamento de moedas. */
    protected $crawlCurrencyService;
    
    /** @var ICacheService O serviço de cache. */
    protected $cacheService;

    /** @var ICrawlCurrencyIso4217Observer O observador de rastreamento de moedas. */
    protected $crawlCurrencyIso4217Observer;

    /** @var array Os dados filtrados das moedas. */
    protected $dataFiltred;

    /** @var int O tempo de armazenamento em cache em minutos. */
    protected $cacheTimingInMinutes;

    /**
     * Construtor da classe.
     *
     * @param ICrawlCurrencyService $crawlCurrencyService O serviço de rastreamento de moedas.
     * @param ICacheService $cacheService O serviço de cache.
     * @param ICrawlCurrencyIso4217Observer $crawlCurrencyIso4217Observer O observador de rastreamento de moedas.
     */
    public function __construct(ICrawlCurrencyService $crawlCurrencyService, ICacheService $cacheService, ICrawlCurrencyIso4217Observer $crawlCurrencyIso4217Observer)
    {        
        $this->crawlCurrencyService = $crawlCurrencyService;
        $this->crawlCurrencyIso4217Observer = $crawlCurrencyIso4217Observer;
        $this->cacheService = $cacheService;
        $this->dataFiltred = [];
        $this->cacheTimingInMinutes = 720;
    }
    
    /**
     * Encontra moedas com base nos filtros fornecidos.
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @return array|null Um array contendo as moedas encontradas ou null se não houver correspondências.
     */
    public function find(array $filter): ?array
    {
        $cachedResult = $this->cacheService->get($this->getKeyFilter($filter));

        return $cachedResult;
    }

    /**
     * Encontra moedas realizando um processo de rastreamento (crawling).
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @return array|null Um array contendo as moedas encontradas ou null se não houver correspondências.
     */
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

    /**
     * Cria ou atualiza os dados filtrados das moedas.
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @param array $data Os dados extraídos das moedas.
     * @return array Os dados filtrados das moedas.
     */
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

    /**
     * Obtém a chave de filtro para o cache.
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @return string A chave de filtro para o cache.
     */
    private function getKeyFilter(array $filter): string
    {
        return json_encode($filter);
    }

    /**
     * Armazena os dados filtrados das moedas em cache.
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @return void
     */
    private function storeCache(array $filter): void 
    {
        $this->cacheService->put($this->getKeyFilter($filter), $this->dataFiltred, now()->addMinutes($this->cacheTimingInMinutes));
    }

    /**
     * Tenta adicionar um item aos dados filtrados das moedas.
     *
     * @param array $data Os dados brutos obtidos durante o processo de rastreamento.
     * @param string $keyFind A chave pela qual os dados serão filtrados.
     * @param string $filterValue O valor a ser filtrado.
     * @return void
     */
    private function tryAddDataItem(array $data, string $keyFind, string $filterValue): void
    {
        $mapedData = $this->mapData($data, $keyFind, $filterValue);
        if ($mapedData)
            $this->dataFiltred[] = $mapedData;
    }

    /**
     * Filtra os dados brutos para encontrar o item correspondente ao valor do filtro.
     *
     * @param array $data Os dados brutos obtidos durante o processo de rastreamento.
     * @param string $keyFind A chave pela qual os dados serão filtrados.
     * @param string $filterValue O valor a ser filtrado.
     * @return mixed|null O item filtrado dos dados brutos, ou null se não for encontrado.
     */
    private function mapData(array $data, string $keyFind, string $filterValue)
    {
        $dataByFilter = array_filter($data, function ($value) use (&$keyFind, &$filterValue) {
            
            return strtoupper($value[$keyFind]) == strtoupper($filterValue);
        });

       return $dataByFilter[array_key_first($dataByFilter)];
    }
}
