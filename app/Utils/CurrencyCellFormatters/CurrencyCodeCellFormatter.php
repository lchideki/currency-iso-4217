<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

/**
 * Class CurrencyCodeCellFormatter
 *
 * Classe para formatação de células de código de moeda.
 */
class CurrencyCodeCellFormatter implements ICurrencyCellFormatter
{
     /**
     * Formata o conteúdo da célula de código de moeda.
     *
     * @param DOMElement $cell A célula DOM contendo o código de moeda.
     * @return string O código de moeda formatado.
     */
    public function format(DOMElement $cell): string
    {
        return strtoupper(trim($cell->nodeValue));
    }
}
