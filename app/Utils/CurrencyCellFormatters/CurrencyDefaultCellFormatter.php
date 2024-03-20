<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

/**
 * Class CurrencyDefaultCellFormatter
 *
 * Classe para formatação padrão de células de moeda.
 */
class CurrencyDefaultCellFormatter implements ICurrencyCellFormatter
{
    /**
     * Formata o conteúdo da célula de moeda de forma padrão.
     *
     * @param DOMElement $cell A célula DOM contendo o valor de moeda.
     * @return string O valor de moeda formatado.
     */
    public function format(DOMElement $cell): string
    {
        return trim($cell->nodeValue);
    }
}
