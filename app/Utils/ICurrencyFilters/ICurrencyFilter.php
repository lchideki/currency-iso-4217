<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

interface ICurrencyFilter
{
    public function filter($filters);
}
