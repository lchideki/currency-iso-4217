<?php

namespace App\Observers;

use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class WikipediaIso4217CrawlerObserver extends CrawlObserver
{
    private $pages =[];

    public function willCrawl(UriInterface $uri, ?string $linkText): void {
        echo "Now crawling: " . (string) $uri . PHP_EOL;
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     * @param string|null $linkText
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null,
    ): void {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        $tableIsoCodesForCurrency = $doc->getElementById("Códigos_ISO_para_moedas")->parentElement->nextElementSibling;     
        $tableRows = $tableIsoCodesForCurrency->getElementsByTagName('tbody')[0]->getElementsByTagName('tr');
        
        for ($i = 0; $i < $tableRows->length; $i++) {
            $row = $tableRows->item($i);
            $cells = $row->getElementsByTagName('td');

            $rowData = [];
            foreach ($cells as $key => $cell) {
                $rowData[] = $cell->nodeValue;
            }

            dump($rowData);
        }
        
        exit;
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     * @param string|null $linkText
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null
    ): void {
        echo 'failed';
    }

    public function finishedCrawling(): void
    {
        echo 'crawled ' . count($this->pages) . ' urls' . PHP_EOL;
        foreach ($this->pages as $page){
            echo sprintf("Url  path: %s Page title: %s%s", $page['path'], $page['title'], PHP_EOL);
        }
    }
}