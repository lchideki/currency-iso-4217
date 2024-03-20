<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

class CurrencyDecimalDigitsCellFormatter implements ICurrencyCellFormatter
{
    public function format(DOMElement $cell): ?float
    {
        $decimalDigits = $cell->nodeValue;
        return is_numeric($decimalDigits) ? floatval($decimalDigits) : null;
    }
}
