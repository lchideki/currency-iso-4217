<?php

namespace App\Services;

use App\Services\ICurrencyService;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

/**
 * Class CrawlCurrencyService
 * 
 * Serviço para realizar o rastreamento de dados de moedas a partir de um documento DOM.
 */
class CrawlCurrencyService implements ICrawlCurrencyService
{
    /** @var array Os formateadores de células de moeda utilizados para extrair os dados. */
    protected $currencyFormatters;

    /** @var array Os nomes das chaves das colunas da tabela de moedas. */
    protected $tableKeyNames;
    
    /**
     * Cria uma nova instância do serviço de rastreamento de moedas.
     */
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

    /**
     * Processa um objeto DOMDocument para extrair dados de moedas com base nos filtros fornecidos.
     *
     * @param DOMDocument $doc O objeto DOMDocument contendo o documento HTML a ser processado.
     * @param array $requestFilter Os filtros a serem aplicados na extração de dados.
     * @return array Um array contendo os dados de moedas extraídos.
     */
    public function processDomToData(DOMDocument $doc, array $requestFilter): array
    {
        $arrayCurrencyData = $this->loadDataFromTableCurrency($doc);
        return $arrayCurrencyData;
    }

    /**
     * Carrega os dados da tabela de moedas a partir de um objeto DOMDocument.
     *
     * @param DOMDocument $doc O objeto DOMDocument contendo o documento HTML.
     * @return array Os dados extraídos da tabela de moedas.
     */
    private function loadDataFromTableCurrency(DOMDocument $doc): array
    {
        $tableCurrencyDomDocument = $this->loadDomDocumentTableCurrency($doc);
        $tableDataRowsCurrencyDomDocument = $this->loadDomDocumentTableRowsCurrency($tableCurrencyDomDocument);
        $data = $this->loadDataFromTableRowsDom($tableDataRowsCurrencyDomDocument);

        return $data;
    }

    /**
     * Carrega o elemento da tabela de moedas a partir do DOM.
     *
     * @param DOMDocument $doc O objeto DOMDocument contendo o documento HTML.
     * @return DOMElement O elemento da tabela de moedas.
     */
    private function loadDomDocumentTableCurrency(DOMDocument $doc): DOMElement 
    {
        $tableIsoCodesForCurrency = $doc->getElementById("Códigos_ISO_para_moedas")->parentElement->nextElementSibling;     
        return $tableIsoCodesForCurrency;
    }

    /**
     * Carrega as linhas da tabela de moedas a partir do DOM.
     *
     * @param DOMElement $tableIsoCodesForCurrency O elemento da tabela de moedas.
     * @return DOMNodeList A lista de nós representando as linhas da tabela de moedas.
     */
    private function loadDomDocumentTableRowsCurrency(DOMElement $tableIsoCodesForCurrency): DOMNodeList 
    {        
        return $tableIsoCodesForCurrency->getElementsByTagName('tbody')[0]->getElementsByTagName('tr');
    }

    /**
     * Extrai os dados das linhas da tabela de moedas.
     *
     * @param DOMNodeList $tableRowsDom A lista de nós representando as linhas da tabela de moedas.
     * @return array Os dados extraídos das linhas da tabela de moedas.
     */
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

    /**
     * Formata uma célula de moeda com base no tipo de formatação associado à chave da célula.
     *
     * @param DOMElement $cell A célula DOMElement a ser formatada.
     * @param int $cellKey A chave da célula, usada para encontrar o formatador correto.
     * @return mixed O valor formatado da célula de moeda.
     */
    private function formatCell(DOMElement $cell, int $cellKey)
    {
        $formatter = $this->currencyFormatters[$cellKey] ?? new \App\Utils\CurrencyCellFormatters\CurrencyDefaultCellFormatter();

        return $formatter->format($cell);
    }
}
