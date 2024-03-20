<?php

namespace App\Observers;

use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Services\ICrawlCurrencyService;
use Illuminate\Support\Facades\Log;

/**
 * Class CrawlCurrencyIso4217Observer
 * 
 * Observador de rastreamento para moedas ISO 4217.
 */
class CrawlCurrencyIso4217Observer extends CrawlObserver implements ICrawlCurrencyIso4217Observer
{
    /**
     * O serviço de rastreamento de moeda a ser utilizado.
     *
     * @var ICrawlCurrencyService
     */
    protected $currencyService;

    /**
     * O filtro a ser aplicado durante o processo de rastreamento.
     *
     * @var array
     */
    protected $requestFilter;

    /**
     * O resultado do processo de rastreamento.
     *
     * @var array
     */
    protected $result = [];


    /**
     * Cria uma nova instância do observador de rastreamento.
     *
     * @param ICrawlCurrencyService $crawlCurrencyService O serviço de rastreamento de moeda.
     */
    public function __construct(ICrawlCurrencyService $crawlCurrencyService)
    {
        $this->crawlCurrencyService = $crawlCurrencyService;
        
    }

    /**
     * Define o filtro a ser aplicado durante o processo de rastreamento.
     *
     * @param array $requestFilter O filtro a ser aplicado.
     * @return void
     */
    public  function setFilter($requestFilter)
    {
        $this->requestFilter = $requestFilter;
    }

    /**
     * Chamado quando o rastreador está prestes a rastrear a URL.
     *
     * @param UriInterface $uri A URI que será rastreada.
     * @param string|null $linkText O texto do link que leva à URI.
     * @return void
     */
    public function willCrawl(UriInterface $uri, ?string $linkText): void 
    {
      
    }

    /**
     * Chamado quando o rastreador rastreou com sucesso a URL especificada.
     *
     * @param UriInterface $url A URI que foi rastreada com sucesso.
     * @param ResponseInterface $response A resposta HTTP da requisição.
     * @param UriInterface|null $foundOnUrl A URI onde o link foi encontrado.
     * @param string|null $linkText O texto do link que leva à URI.
     * @return void
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null,
    ): void {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());

        $this->result = $this->crawlCurrencyService->processDomToData($doc, $this->requestFilter);
    }

    /**
     * Chamado quando o rastreador teve um problema ao rastrear a URL especificada.
     *
     * @param UriInterface $url A URI que teve problemas durante o rastreamento.
     * @param RequestException $requestException A exceção lançada durante o rastreamento.
     * @param UriInterface|null $foundOnUrl A URI onde o link foi encontrado.
     * @param string|null $linkText O texto do link que leva à URI.
     * @return void
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null
    ): void 
    {
        Log::error('crawlFailed: ' . $url);
    }

    /**
     * Chamado quando o rastreamento de todas as URLs foi concluído.
     *
     * @return void
     */
    public function finishedCrawling(): void
    {
        
    }

    /**
     * Obtém o resultado do processo de rastreamento.
     *
     * @return array O resultado do rastreamento.
     */
    public function getResult() 
    {
        return $this->result;
    }
}