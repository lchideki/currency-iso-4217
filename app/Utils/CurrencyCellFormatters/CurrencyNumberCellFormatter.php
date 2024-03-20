<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

/**
 * Class CurrencyNumberCellFormatter
 *
 * Classe para formatação de células de número de moeda.
 */
class CurrencyNumberCellFormatter implements ICurrencyCellFormatter
{
    /**
     * Formata o conteúdo da célula de número de moeda.
     *
     * @param DOMElement $cell A célula DOM contendo o número de moeda.
     * @return string|null O número de moeda formatado, ou null se vazio.
     */
    public function format(DOMElement $cell): ?string
    {
        return trim($cell->nodeValue);
    }
}
