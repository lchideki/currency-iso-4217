<?php

namespace App\Services;

use DOMDocument;

/**
 * Interface ICrawlCurrencyService
 * 
 * Interface para o serviço de rastreamento de moedas.
 */
interface ICrawlCurrencyService
{
    /**
     * Processa um objeto DOMDocument para extrair dados de moedas com base nos filtros fornecidos.
     *
     * @param DOMDocument $doc O objeto DOMDocument contendo o documento HTML a ser processado.
     * @param array $requestFilter Os filtros a serem aplicados na extração de dados.
     * @return array Um array contendo os dados de moedas extraídos.
     */
    public function processDomToData(DOMDocument $doc, array $requestFilter): array;
}
