<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

/**
 * Class CurrencyNameCellFormatter
 *
 * Classe para formatação de células de nome de moeda.
 */
class CurrencyNameCellFormatter implements ICurrencyCellFormatter
{
    /**
     * Formata o conteúdo da célula de nome de moeda para maiúsculas e sem espaços em branco.
     *
     * @param DOMElement $cell A célula DOM contendo o nome de moeda.
     * @return string O nome de moeda formatado.
     */
    public function format(DOMElement $cell): string
    {
        return strtoupper(trim($cell->nodeValue));
    }
}
