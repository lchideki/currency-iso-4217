<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;
use DomXPath;

/**
 * Class CurrencyLocationCellFormatter
 *
 * Classe para formatação de células de localização de moeda.
 */
class CurrencyLocationCellFormatter implements ICurrencyCellFormatter
{
    /**
     * Formata o conteúdo da célula de localização de moeda.
     *
     * @param DOMElement $cell A célula DOM contendo a localização de moeda.
     * @return array Um array contendo as informações de localização formatadas.
     */
    public function format(DOMElement $cell): array
    {
      
        $iconsNodes = $cell->getElementsByTagName('img');

        $locations = [];

        if ($iconsNodes->length > 0) 
        {
            foreach ($iconsNodes as $key => $icon) 
            {
                $location = [];
                $location['icon'] =  $icon->getAttribute('src');
                $location['location'] = $this->getTextFromIcon($icon);

                $locations[] = $location;
            }
        }
        else
        {
            $locations[] = [ 'location' => $cell->nodeValue ];
        }

        return $locations;
    }

    /**
     * Obtém o texto associado a um ícone de localização.
     *
     * @param DOMElement $elem O elemento DOM representando o ícone.
     * @return string|null O texto associado ao ícone, ou null se não for encontrado.
     */
    private function getTextFromIcon(DOMElement $elem)
    {
        if ($elem->parentNode->tagName == 'span')
            return $this->getTextFromIcon($elem->parentElement);
        else return $elem->nextElementSibling->nodeValue ?? null;
    }
}
