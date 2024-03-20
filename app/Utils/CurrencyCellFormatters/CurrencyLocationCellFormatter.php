<?php

namespace App\Utils\CurrencyCellFormatters;
use DOMElement;
use DomXPath;

class CurrencyLocationCellFormatter implements ICurrencyCellFormatter
{
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

    private function getTextFromIcon(DOMElement $elem)
    {
        if ($elem->parentNode->tagName == 'span')
            return $this->getTextFromIcon($elem->parentElement);
        else return $elem->nextElementSibling->nodeValue ?? null;
    }
}
