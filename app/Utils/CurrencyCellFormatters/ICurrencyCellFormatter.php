<?php

namespace App\Utils\CurrencyCellFormatters;

use DOMElement;

/**
 * Interface ICurrencyCellFormatter
 *
 * Interface para formatação de células de moeda.
 */
interface ICurrencyCellFormatter
{
    /**
     * Formata o conteúdo da célula de moeda.
     *
     * @param DOMElement $cell A célula DOM contendo o conteúdo a ser formatado.
     * @return mixed O conteúdo formatado da célula.
     */
    public function format(DOMElement $cell);
}
