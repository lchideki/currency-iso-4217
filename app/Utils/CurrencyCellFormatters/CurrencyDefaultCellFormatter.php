<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

class CurrencyDefaultCellFormatter implements ICurrencyCellFormatter
{
    public function format(DOMElement $cell): string
    {
        return trim($cell->nodeValue);
    }
}
