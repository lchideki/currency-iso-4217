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
 * Interface ICrawlCurrencyIso4217Observer
 * 
 * Interface para um observador de rastreamento de moedas ISO 4217.
 */
interface ICrawlCurrencyIso4217Observer
{
    /**
     * Define o filtro a ser aplicado durante o processo de rastreamento.
     *
     * @param array $requestFilter O filtro a ser aplicado.
     * @return void
     */
    public function setFilter($requestFilter);
}