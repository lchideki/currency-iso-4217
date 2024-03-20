<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Observers\WikipediaIso4217CrawlerObserver;
use App\Services\ICurrencyService;

class CrawlData extends Command
{
    protected $signature = 'crawl:data';
    protected $description = 'Crawl data from a website using spatie/crawler';
    protected $currencyService;

    public function __construct(ICurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        parent::__construct();
    }

    public function handle()
    {
        Crawler::create()
            ->setCrawlObserver(new WikipediaIso4217CrawlerObserver($this->currencyService))
            ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');
    }
}