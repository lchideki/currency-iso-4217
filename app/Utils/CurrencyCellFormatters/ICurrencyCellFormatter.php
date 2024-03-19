<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

interface ICurrencyCellFormatter
{
    public function format(DOMElement $cell);
}
