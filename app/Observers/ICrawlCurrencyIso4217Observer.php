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


interface ICrawlCurrencyIso4217Observer
{
    public function setFilter($requestFilter);
}