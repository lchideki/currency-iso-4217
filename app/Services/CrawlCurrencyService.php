<?php

namespace App\Services;

use App\Services\ICurrencyService;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class CrawlCurrencyService implements ICrawlCurrencyService
{
 
    protected $currencyFormatters;
    protected $tableKeyNames;
    
    public function __construct()
    {
        $this->currencyFormatters = [
            0 => new \App\Utils\CurrencyCellFormatters\CurrencyCodeCellFormatter(),
            1 => new \App\Utils\CurrencyCellFormatters\CurrencyNumberCellFormatter(),
            2 => new \App\Utils\CurrencyCellFormatters\CurrencyDecimalDigitsCellFormatter(),
            3 => new \App\Utils\CurrencyCellFormatters\CurrencyNameCellFormatter(),
            4 => new \App\Utils\CurrencyCellFormatters\CurrencyLocationCellFormatter(),
        ];

        $this->tableKeyNames = [
            0 => 'code',
            1 => 'number',
            2 => 'decimal_digits',
            3 => 'currency',
            4 => 'locations',
        ];
    }

    public function processDomToData(DOMDocument $doc, array $requestFilter): array
    {
        $arrayCurrencyData = $this->loadDataFromTableCurrency($doc);
        return $arrayCurrencyData;
    }

    private function loadDataFromTableCurrency(DOMDocument $doc): array
    {
        $tableCurrencyDomDocument = $this->loadDomDocumentTableCurrency($doc);
        $tableDataRowsCurrencyDomDocument = $this->loadDomDocumentTableRowsCurrency($tableCurrencyDomDocument);
        $data = $this->loadDataFromTableRowsDom($tableDataRowsCurrencyDomDocument);

        return $data;
    }

    private function loadDomDocumentTableCurrency(DOMDocument $doc): DOMElement 
    {
        $tableIsoCodesForCurrency = $doc->getElementById("Códigos_ISO_para_moedas")->parentElement->nextElementSibling;     
        return $tableIsoCodesForCurrency;
    }

    private function loadDomDocumentTableRowsCurrency(DOMElement $tableIsoCodesForCurrency): DOMNodeList 
    {        
        return $tableIsoCodesForCurrency->getElementsByTagName('tbody')[0]->getElementsByTagName('tr');
    }

    private function loadDataFromTableRowsDom(DOMNodeList $tableRowsDom): array 
    {
        $tableRowsData = [];

        foreach ($tableRowsDom as $tableRow) 
        {
            $cells = $tableRow->getElementsByTagName('td');
            
            if (count($cells) <= 0)
                continue;

            $rowData = [];
            
            try 
            {
                foreach ($cells as $key => $cell) 
                {
                    $rowData[$this->tableKeyNames[$key]] = $this->formatCell($cell, $key);
                }                   
            } 
            catch (Exception $e) 
            {
                continue;
            }

            $tableRowsData[] = $rowData;
        }

        return $tableRowsData;
    }

    private function formatCell(DOMElement $cell, int $cellKey)
    {
        $formatter = $this->currencyFormatters[$cellKey] ?? new \App\Utils\CurrencyCellFormatters\CurrencyDefaultCellFormatter();

        return $formatter->format($cell);
    }
}
