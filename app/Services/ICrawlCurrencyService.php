<?php

namespace App\Services;
use DOMDocument;

interface ICrawlCurrencyService
{
    public function processDomToData(DOMDocument $doc): void;
}
