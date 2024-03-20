<?php

namespace App\Services;
use DOMDocument;

interface ICrawlCurrencyService
{
    public function processDomToData(DOMDocument $doc, array $requestFilter): array;
    // public function find(array $filters): ?array;
}
