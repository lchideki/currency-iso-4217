<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;

/**
 * Class CurrencyDecimalDigitsCellFormatter
 *
 * Classe para formatação de células de dígitos decimais de moeda.
 */
class CurrencyDecimalDigitsCellFormatter implements ICurrencyCellFormatter
{
    /**
     * Formata o conteúdo da célula de dígitos decimais de moeda.
     *
     * @param DOMElement $cell A célula DOM contendo os dígitos decimais de moeda.
     * @return float|null Os dígitos decimais de moeda formatados como um float, ou null se não for um número.
     */
    public function format(DOMElement $cell): ?float
    {
        $decimalDigits = $cell->nodeValue;
        return is_numeric($decimalDigits) ? floatval($decimalDigits) : null;
    }
}
