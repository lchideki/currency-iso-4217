<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

class CurrencyNameCellFormatter implements ICurrencyCellFormatter
{
    public function format(DOMElement $cell): string
    {
        return strtoupper(trim($cell->nodeValue));
    }
}
