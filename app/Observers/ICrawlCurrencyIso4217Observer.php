<?php

namespace App\Observers;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface ICrawlCurrencyIso4217Observer
{
    public function willCrawl(UriInterface $uri, ?string $linkText): void;

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null
    ): void;

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null
    ): void;

    public function finishedCrawling(): void;
}
