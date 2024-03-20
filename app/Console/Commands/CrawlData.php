<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Services\ICrawlCurrencyService;
use App\Observers\CrawlCurrencyIso4217Observer;

class CrawlData extends Command
{
    protected $signature = 'crawl:data';
    protected $description = 'Crawl data from a website using spatie/crawler';
    protected $crawlCurrencyService;

    public function __construct(ICrawlCurrencyService $crawlCurrencyService)
    {
        $this->crawlCurrencyService = $crawlCurrencyService;
        parent::__construct();
    }

    public function handle()
    {
        Crawler::create()
            ->setCrawlObserver(new CrawlCurrencyIso4217Observer($this->crawlCurrencyService , []))
            ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');
    }
}