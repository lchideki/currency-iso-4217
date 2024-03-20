<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Observers\ICrawlCurrencyIso4217Observer;

class CrawlData extends Command
{
    protected $signature = 'crawl:data';
    protected $description = 'Crawl data from a website using spatie/crawler';
    protected $crawlCurrencyObserver;

    public function __construct(ICrawlCurrencyIso4217Observer $crawlCurrencyObserver)
    {
        $this->crawlCurrencyObserver = $crawlCurrencyObserver;
        parent::__construct();
    }

    public function handle()
    {
        $tableRowsData = Crawler::create()
            ->setCrawlObserver($this->crawlCurrencyObserver)
            ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');
    }
}