<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Observers\WikipediaIso4217CrawlerObserver;

class CrawlData extends Command
{
    protected $signature = 'crawl:data';
    protected $description = 'Crawl data from a website using spatie/crawler';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Crawler::create()
            ->setCrawlObserver(new WikipediaIso4217CrawlerObserver())
            ->startCrawling('https://pt.wikipedia.org/wiki/ISO_4217');
    }
}